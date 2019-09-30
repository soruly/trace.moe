<?php
ini_set("display_errors", 0);
require 'config.php';

if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){
    //request from AJAX, continue
}
else if( isset( $_SERVER['HTTP_REFERER'] ) && ( $_SERVER['HTTP_REFERER'] == 'https://trace.moe/' ) ){
    //request from original site, continue
}
else{
    exit(header("HTTP/1.0 403 Forbidden", true, 403));
}

require 'vendor/autoload.php';

use Predis\Collection\Iterator;
Predis\Autoloader::register();
$redis = new Predis\Client('tcp://127.0.0.1:6379');
$redis->connect();

if (isset($_POST['data']) || isset($_FILES['image'])) {

    $client_id = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $limit_id = $client_id."_limit"; // request per minute
    $quota_id = $client_id."_quota"; // quota per day

    // rate limit per minute
    if(!$redis->exists($limit_id)){
        $redis->set($limit_id, 10);
        $redis->expire($limit_id, 60);
    }
    $limit = intval($redis->get($limit_id));
    $limit--;
    $limit_ttl = $redis->ttl($limit_id);
    $redis->set($limit_id, $limit);
    $redis->expire($limit_id, $limit_ttl);
    if($limit < 0) {
        header("HTTP/1.0 429 Too Many Requests");
        header("Retry-After: ".$limit_ttl);
        exit("You have searched too much, try again in ".$limit_ttl." seconds.");
    }

    // quota limit per day
    if(!$redis->exists($quota_id)){
        $redis->set($quota_id, 150);
        $redis->expire($quota_id, 86400);
    }
    $quota = intval($redis->get($quota_id));
    $quota--;
    $quota_ttl = $redis->ttl($quota_id);
    $redis->set($quota_id, $quota);
    $redis->expire($quota_id, $quota_ttl);
    if($quota < 0) {
        header("HTTP/1.0 429 Too Many Requests");
        header("Retry-After: ".$quota_ttl);
        exit("You have searched too much, try again in ".$quota_ttl." seconds.");
    }

    header('Content-Type: application/json');

    $savePath = './temp/';
    $filename = microtime(true).'.jpg';

    if (isset($_FILES['image'])) {
        $data = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $data = str_replace('data:image/jpeg;base64,', '', $_POST['data']);
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);
    }
    // file_put_contents($savePath.$filename, $data);
    $crop = true;
    if($crop){
      file_put_contents("thumbnail/".$filename, $data);
      exec("python crop.py thumbnail/".$filename." ".$savePath.$filename);
      // exec("python crop.py thumbnail/".$filename." thumbnail/".$filename.".jpg");
      unlink("thumbnail/".$filename);
    } else {
      file_put_contents($savePath.$filename, $data);
    }
    
    $final_result = new stdClass;
    $final_result->CacheHit = false;
    $final_result->RawDocsCount = 0;
    $final_result->RawDocsSearchTime = 0;
    $final_result->ReRankSearchTime = 0;
    $final_result->docs = [];
    

    $filter = "";
    if(isset($_POST['filter'])){
        $filter = $_POST['filter'] ? "fq=id:".intval($_POST['filter'])."/*" : "";
    }
    $trial = 0;
    if(isset($_POST['trial']) && intval($_POST['trial'])){
        $trial = intval($_POST['trial']) > 5 ? 5 : intval($_POST['trial']);
    }

    $candidates = 1000000;
    $accuracy = $trial;

    unset($nodes);
    for($i = 0; $i <= 31; $i++){
        $nodes[]= "http://192.168.2.12:8983/solr/lire_{$i}/lireq?{$filter}&field=cl_ha&ms=false&accuracy={$accuracy}&candidates={$candidates}&rows=10";
    }

    $node_count = count($nodes);

    $curl_arr = array();
    $master = curl_multi_init();

    for($i = 0; $i < $node_count; $i++)
    {
        $url = $nodes[$i];
        $curl_arr[$i] = curl_init($url);
        curl_setopt($curl_arr[$i], CURLOPT_POST, true);
        curl_setopt($curl_arr[$i], CURLOPT_HTTPHEADER, array("Content-Type: text/plain"));
        curl_setopt($curl_arr[$i], CURLOPT_POSTFIELDS, file_get_contents($savePath.$filename));
        curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($master, $curl_arr[$i]);
    }

    do {
        curl_multi_exec($master,$running);
    } while($running > 0);


    for($i = 0; $i < $node_count; $i++)
    {
        $results[] = curl_multi_getcontent($curl_arr[$i]);
    }

    foreach ($results as $res) {
        $result = json_decode($res);
        $final_result->RawDocsCount += intval($result->RawDocsCount);
        $final_result->RawDocsSearchTime += intval($result->RawDocsSearchTime);
        $final_result->ReRankSearchTime += intval($result->ReRankSearchTime);
        if(intval($result->RawDocsCount) > 0){
          $final_result->docs = array_merge($final_result->docs,$result->response->docs);
          usort($final_result->docs, "reRank");
        }
    }
    $final_result->docs = array_slice($final_result->docs, 0, 20);
    
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    
    //combine adjacent time frames
    $docs = [];
    if(isset($final_result->RawDocsCount)){
        foreach($final_result->docs as $key => $doc){
            $path = explode('/',$doc->id)[0].'/'.explode('/',$doc->id)[1];
            $t = floatval(explode('/',$doc->id)[2]);
            $doc->from = $t;
            $doc->to = $t;
            $matches = 0;
            foreach($docs as $key2 => $doc2){
                if($doc->id == $doc2->id){ //remove duplicates
                    $matches = 1;
                    continue;
                }
                $path2 = explode('/',$doc2->id)[0].'/'.explode('/',$doc2->id)[1];
                $t2 = floatval(explode('/',$doc2->id)[2]);
                if($doc->id != $doc2->id && $path == $path2 && abs($t - $t2) < 2){
                    $matches++;
                    if($t < $doc2->from)
                        $docs[$key2]->from = $t;
                    if($t > $doc2->to)
                        $docs[$key2]->to = $t;
                }
            }
            if($matches == 0){
                $docs[] = $doc;
            }
        }
    }
    
    foreach($docs as $key => $doc) {
        $path = explode('/',$doc->id)[0].'/'.explode('/',$doc->id)[1];
        $t = floatval(explode('/',$doc->id)[2]);
        //$from = floatval(explode('?t=',$doc->id)[1]);
        //$to = floatval(explode('?t=',$doc->id)[1]);
        $start = round($doc->from - 16, 2);
        if($start < 0) $start = 0;
        $end = round($doc->to + 4);

        $dir = explode('/',$path)[0];
        $subdir = explode('/',$path)[1];
        

        $anilist_id = intval(explode('/',$path)[0]);
        $doc->anilist_id = $anilist_id;

        $file = explode('/',$path)[1];
        $episode = filename_to_episode($file);

        $doc->i = $key;
        $doc->start = $start;
        $doc->end = $end;
        $doc->t = $t;
        $doc->file = $file;
        $doc->episode = $episode;
        $expires = time() + 300;
        $doc->expires = $expires;
        $token = str_replace(array('+','/','='),array('-','_',''),base64_encode(md5('/'.$path.$start.$end.$secretSalt,true)));
        $doc->token = $token;
        $tokenthumb = str_replace(array('+','/','='),array('-','_',''),base64_encode(md5($t.$secretSalt,true)));
        $doc->tokenthumb = $tokenthumb;
        $doc->diff = $doc->d;
        unset($doc->id);
        unset($doc->d);

        // use folder name as default title
        $doc->title = $file;
        $doc->title_native = null;
        $doc->title_chinese = null;
        $doc->title_english = null;
        $doc->title_romaji = null;
        $doc->is_adult = false;

        // use folder path to get anilist ID



        // use anilist ID to get titles of different languages
        $request = array(
        "size" => 1,
        "_source" => array("title", "isAdult"),
        "query" => array(
            "ids" => array(
                "values" => array(intval($anilist_id))
            )
        )
        );
        $payload = json_encode($request);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1:9200/anilist/anime/_search");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        try{
          $res = curl_exec($curl);
          $result = json_decode($res);
          if($result->hits && $result->hits->total > 0){
            $doc->title_romaji = $result->hits->hits[0]->_source->title->romaji ?? "";
            $doc->title_native = $result->hits->hits[0]->_source->title->native ?? $doc->title_romaji;
            $doc->title_english = $result->hits->hits[0]->_source->title->english ?? $doc->title_romaji;
            $doc->title_chinese = $result->hits->hits[0]->_source->title->chinese ?? $doc->title_romaji;
            $doc->is_adult = $result->hits->hits[0]->_source->isAdult;
          }
        }
        catch(Exception $e){
            echo $e;
            exit();
        }
        finally{
          curl_close($curl);
        }
    }
    //unset($final_result->docs);
    $final_result->docs = $docs;
    //unset($final_result->RawDocsCount);
    //unset($final_result->RawDocsSearchTime);
    //unset($final_result->ReRankSearchTime);
    unset($final_result->responseHeader);
    //$final_result->accuracy = $accuracy;
    $final_result->limit = $limit;
    $final_result->limit_ttl = $limit_ttl;
    $final_result->quota = $quota;
    $final_result->quota_ttl = $quota_ttl;
    echo json_encode($final_result);
    unlink($savePath.$filename);
}

function reRank($a, $b){
    return ($a->d < $b->d) ? -1 : 1;
}

function filename_to_episode($filename){
    $filename = preg_replace('/\d{4,}/i','',$filename);
    $filename = str_replace([
        '1920',
        '1080',
        '1280',
        '720',
        '576',
        '960',
        '480',
    ], '', $filename);
    if(preg_match('/(?:OVA|OAD)/i',$filename))
        return "OVA/OAD";
    if(preg_match('/\W(?:Special|Preview|Prev)[\W_]/i',$filename))
        return "Special";
    if(preg_match('/\WSP\W{0,1}\d{1,2}/i',$filename))
        return "Special";
    $num = preg_replace('/.+?\[(\d+\.*\d+).{0,4}].+/i','$1',$filename);
    if($num != $filename)
        return floatval($num);
    $num = preg_replace('/.*(?:EP|第) *(\d+\.*\d+).+/i','$1',$filename);
    if($num != $filename)
        return floatval($num);
    $num = preg_replace('/^(\d+\.*\d+).{0,4}.+/i','$1',$filename); //start with %num
    if($num != $filename)
        return floatval($num);
    
    $num = preg_replace('/.+? - (\d+\.*\d+).{0,4}.+/i','$1',$filename); // - %num
    if($num != $filename)
        return floatval($num);
    
    $num = preg_replace('/.+? (\d+\.*\d+).{0,4} .+/i','$1',$filename); // %num 
    if($num != $filename)
        return floatval($num);
    
    $num = preg_replace('/.*?(\d+\.*\d+)\.mp4/i','$1',$filename);
    if($num != $filename)
        return floatval($num);
    
    $num = preg_replace('/.+? (\d+\.*\d+).{0,4}.+/i','$1',$filename);
    if($num != $filename)
        return floatval($num);
    return "";
}
?>
