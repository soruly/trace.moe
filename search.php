<?php
ini_set("display_errors", 0);
require 'config.php';

header('Content-Type: application/json');

if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){
    //request from AJAX, continue
}
else if( isset( $_SERVER['HTTP_REFERER'] ) && ( $_SERVER['HTTP_REFERER'] == 'https://whatanime.ga/' ) ){
    //request from original site, continue
}
else{
    exit(header("HTTP/1.0 403 Forbidden", true, 403));
}

require 'vendor/autoload.php';

use Predis\Collection\Iterator;
Predis\Autoloader::register();
$redis = new Predis\Client('tcp://127.0.0.1:6379');
$redis_alive = true;
try {
    $redis->connect();
}
catch (Predis\Connection\ConnectionException $exception) {
    $redis_alive = false;
}

$lang = 'en';

if($redis->exists($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $quota = intval($redis->get($_SERVER['HTTP_X_FORWARDED_FOR']));
}
else{
    $quota = 30;
    $redis->set($_SERVER['HTTP_X_FORWARDED_FOR'], $quota);
    $redis->expire($_SERVER['HTTP_X_FORWARDED_FOR'], 600);
}

if(isset($_POST['data'])){
    if($quota < 1){
        exit(header("HTTP/1.0 429 Too Many Requests"));
    }
    $quota--;
    $expire = $redis->ttl($_SERVER['HTTP_X_FORWARDED_FOR']);
    $redis->set($_SERVER['HTTP_X_FORWARDED_FOR'], $quota);
    $redis->expire($_SERVER['HTTP_X_FORWARDED_FOR'], $expire);

    $savePath = '/usr/share/nginx/html/pic/';
    $filename = microtime(true).'.jpg';
    $data = str_replace('data:image/jpeg;base64,', '', $_POST['data']);
	$data = str_replace(' ', '+', $data);

    // file_put_contents($savePath.$filename, base64_decode($data));
    $crop = true;
    if($crop){
      file_put_contents("thumbnail/".$filename, base64_decode($data));
      exec("python crop.py thumbnail/".$filename." ".$savePath.$filename);
      // exec("python crop.py thumbnail/".$filename." thumbnail/".$filename.".jpg");
      unlink("thumbnail/".$filename);
    }
    else{
      file_put_contents($savePath.$filename, base64_decode($data));
    }
    
    //extract image feature
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://192.168.2.11:8983/solr/anime_cl/lireq?field=cl_ha&extract=http://192.168.2.11/pic/".$filename);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    try{
      $res = curl_exec($curl);
      $extract_result = json_decode($res);
      $cl_hi = $extract_result->histogram;
      $cl_ha = $extract_result->hashes;
      $cl_hi_key = "cl_hi:".$cl_hi;
    }
    catch(Exception $e){
    }
    finally{
      curl_close($curl);
    }
    
    $final_result = new stdClass;
    $final_result->CacheHit = false;
    $final_result->docs = [];
    

    $filter = '*';
    if(isset($_POST['filter'])){
        $filter = str_replace('"','',rawurldecode($_POST['filter']));
    }
    $trial = 4;
    if(isset($_POST['trial']) && intval($_POST['trial'])){
        $trial = intval($_POST['trial']) > 12 ? 12 : intval($_POST['trial']);
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://192.168.2.11:8983/solr/anime_cl/lireq?filter=".rawurlencode($filter)."&field=cl_ha&accuracy=".$trial."&candidates=5000000&rows=10&feature=".$cl_hi."&hashes=".implode($cl_ha,","));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    try{
      $res = curl_exec($curl);
      $result = json_decode($res);
      $final_result->RawDocsCount = intval($result->RawDocsCount);
      $final_result->RawDocsSearchTime = intval($result->RawDocsSearchTime);
      $final_result->ReRankSearchTime = intval($result->ReRankSearchTime);
      if(intval($result->RawDocsCount) > 0){
          $final_result->docs = array_merge($final_result->docs,$result->docs);
          usort($final_result->docs, "reRank");
      }
    }
    catch(Exception $e){
    }
    finally{
      curl_close($curl);
    }

    $final_result->quota = $quota;
    $final_result->expire = $expire;
    
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    
    //combine adjacent time frames
    $docs = [];
    if(isset($final_result->RawDocsCount)){
        foreach($final_result->docs as $key => $doc){
            $path = explode('?t=',$doc->id)[0];
            $t = floatval(explode('?t=',$doc->id)[1]);
            $doc->from = $t;
            $doc->to = $t;
            $matches = 0;
            foreach($docs as $key2 => $doc2){
                if($doc->id == $doc2->id){ //remove duplicates
                    $matches = 1;
                    continue;
                }
                $path2 = explode('?t=',$doc2->id)[0];
                $t2 = floatval(explode('?t=',$doc2->id)[1]);
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
        $path = explode('?t=',$doc->id)[0];
        $t = floatval(explode('?t=',$doc->id)[1]);
        //$from = floatval(explode('?t=',$doc->id)[1]);
        //$to = floatval(explode('?t=',$doc->id)[1]);
        $start = $doc->from - 16;
        if($start < 0) $start = 0;
        $end = $doc->to + 4;

        $dir = explode('/',$path)[0];
        $subdir = explode('/',$path)[1];
        //the following directires has be relocated
        if($subdir == "Fairy Tail")
            $path = str_replace($dir.'/', '2009-10/', $path);
        if($subdir == "Fairy Tail 2014")
            $path = str_replace($dir.'/', '2014-04/', $path);
        if($subdir == "Hunter x Hunter 2011")
            $path = str_replace($dir.'/', '2011-10/', $path);
        if($subdir == "美食的俘虜")
            $path = str_replace($dir.'/', '2011-04/', $path);
        if($subdir == "BLEACH")
            $path = str_replace($dir.'/', '2004-10/', $path);
        if($subdir == "Naruto")
            $path = str_replace($dir.'/', '2002-10/', $path);
        if($subdir == "犬夜叉")
            $path = str_replace($dir.'/', '2000-10/', $path);
        if($subdir == "家庭教師REBORN")
            $path = str_replace($dir.'/', '2006-10/', $path);
        if($subdir == "驅魔少年")
            $path = str_replace($dir.'/', '2006-10/', $path);
        

        $season = explode('/',$path)[0];
        $anime = explode('/',$path)[1];
        $file = explode('/',$path)[2];
        $episode = filename_to_episode($file);

        $doc->i = $key;
        $doc->start = $start;
        $doc->end = $end;
        $doc->t = $t;
        $doc->season = $season;
        $doc->anime = $anime;
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
        $doc->title = $anime;
        $doc->title_native = null;
        $doc->title_chinese = null;
        $doc->title_english = null;
        $doc->title_romaji = null;
        $doc->anilist_id = null;

        // use folder path to get anilist ID
        $sql = mysqli_connect($sql_anime_hostname, $sql_anime_username, $sql_anime_password, $sql_anime_database);
        if (!mysqli_connect_errno()) {
            mysqli_query($sql, "SET NAMES 'utf8'");
            if ($stmt = mysqli_prepare($sql, "SELECT `anilist_id` FROM `anime` WHERE `season`=? AND `title`=? LIMIT 0,1")){
                mysqli_stmt_bind_param($stmt, "ss", $season, $anime);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $anilist_id);
                mysqli_stmt_fetch($stmt);

                if(mysqli_stmt_num_rows($stmt) > 0) {

                    $doc->anilist_id = intval($anilist_id);

                    // use anilist ID to get titles of different languages
                    $request = array(
                    "size" => 1,
                    "_source" => array("title"),
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
                        $doc->title_romaji = $result->hits->hits[0]->_source->title->romaji ?? $anime;
                        $doc->title_native = $result->hits->hits[0]->_source->title->native ?? $doc->title_romaji;
                        $doc->title_english = $result->hits->hits[0]->_source->title->english ?? $doc->title_romaji;
                        $doc->title_chinese = $result->hits->hits[0]->_source->title->chinese ?? $anime;
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
                mysqli_stmt_close($stmt);
            }
            mysqli_close($sql);
        }
    }
    unset($final_result->docs);
    $final_result->docs = $docs;
    //unset($final_result->RawDocsCount);
    //unset($final_result->RawDocsSearchTime);
    //unset($final_result->ReRankSearchTime);
    unset($final_result->responseHeader);
    //$final_result->accuracy = $accuracy;
    echo json_encode($final_result);
    //unlink($savePath.$filename);
}

function reRank($a, $b){
    return ($a->d < $b->d) ? -1 : 1;
}

function filename_to_episode($filename){
	$filename = preg_replace('/\d{4,}/i','',$filename);
	$filename = str_replace("1920","",$filename);
	$filename = str_replace("1080","",$filename);
	$filename = str_replace("1280","",$filename);
	$filename = str_replace("720","",$filename);
	$filename = str_replace("576","",$filename);
	$filename = str_replace("960","",$filename);
	$filename = str_replace("480","",$filename);
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
