<?php
ini_set("display_errors", 0);

header("Content-Security-Policy: default-src 'none'; script-src 'self' www.google-analytics.com static.cloudflareinsights.com; style-src * 'self' 'unsafe-inline'; img-src * 'self' data: blob: media.trace.moe; font-src 'self'; connect-src blob: 'self' api.trace.moe media.trace.moe www.google-analytics.com stats.g.doubleclick.net; media-src blob: 'self' media.trace.moe; object-src 'none'; frame-src www.youtube.com www.google.com; worker-src 'none'; frame-ancestors 'none'; form-action 'self'; block-all-mixed-content; base-uri 'none'; manifest-src 'self'");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Origin: *");
header("Referrer-Policy: no-referrer");
header("X-Content-Type-Options: nosniff");

$image = $_POST['image'] ?? null;

$input = file_get_contents('php://input');
if($input) {
    $data = json_decode($input, true);
    $image = $data["image"] ?? $image;
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit('');
}

if (!$image && !isset($_GET['url']) && !isset($_FILES['image'])) {
    header('HTTP/1.1 400 Bad Request');
    exit('"No image received"');
} else {
    $res = null;
    
    if (isset($_GET['url']) && $_GET['url']) {
        $curl = curl_init("https://api.trace.moe/search?info=basic&cutBorders=1&url=".rawurlencode($_GET['url']));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($curl);
        curl_close($curl);
    } else if (isset($_FILES['image'])) {
        $data = file_get_contents($_FILES['image']['tmp_name']);
        $savePath = '../temp/'.microtime(true).'.jpg';
        file_put_contents($savePath, $data);
        
        $curl = curl_init("https://api.trace.moe/search?info=basic&cutBorders=1");
        curl_setopt($curl, CURLOPT_POST, true);
        $cFile = curl_file_create($savePath);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array('image'=> $cFile));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($curl);
        curl_close($curl);
        unlink($savePath);
    } else {
        $data = strpos($image, ",") === false ? $image : substr($image, strpos($image, ",") + 1);
        $data = str_replace(' ', '+', $data);
        if ($data == "") {
            header('HTTP/1.1 400 Bad Request');
            exit('"Image is empty"');
        }
        $savePath = '../temp/'.microtime(true).'.jpg';
        file_put_contents($savePath, base64_decode($data));
        
        $curl = curl_init("https://api.trace.moe/search?info=basic&cutBorders=1");
        curl_setopt($curl, CURLOPT_POST, true);
        $cFile = curl_file_create($savePath);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array('image'=> $cFile));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($curl);
        curl_close($curl);
        unlink($savePath);
    }
    
    $result = json_decode($res);
    if($result->error){
        header('HTTP/1.1 500 Internal Server Error');
        exit('"'.$result->error.'"');
    }
    
    header("X-whatanime-limit: 10");
    header("X-whatanime-limit-ttl: 60");
    header("X-whatanime-quota: 1000");
    header("X-whatanime-quota-ttl: 86400");
    
    $final_result = new stdClass;
    $final_result->RawDocsCount = 0;
    $final_result->CacheHit = false;
    $final_result->trial = 1;
    $final_result->limit = 10;
    $final_result->limit_ttl = 60;
    $final_result->quota = 1000;
    $final_result->quota_ttl = 86400;
    $final_result->RawDocsCount = intval($result->frameCount);
    $final_result->RawDocsSearchTime = 0;
    $final_result->ReRankSearchTime = 0;
    
    $docs = $result->result;
    foreach($docs as $key => $doc) {
        $doc->anilist_id = $doc->anilist->id;
        $doc->anime = $doc->anilist->title->chinese;
        $doc->at = $doc->from + ($doc->to - $doc->from) / 2;
        $doc->episode = $doc->episode;
        $doc->filename = $doc->filename;
        $doc->from = $doc->from;
        $doc->is_adult = $doc->anilist->isAdult;
        $doc->mal_id = $doc->anilist->idMal;
        $doc->season = "";
        $doc->similarity = $doc->similarity;
        $doc->synonyms = $doc->anilist->synonyms;
        $doc->synonyms_chinese = $doc->anilist->synonyms_chinese;
        $doc->title = $doc->anilist->title->native;
        $doc->title_chinese = $doc->anilist->title->chinese;
        $doc->title_english = $doc->anilist->title->english;
        $doc->title_native = $doc->anilist->title->native;
        $doc->title_romaji = $doc->anilist->title->romaji;
        $doc->to = $doc->to;
        preg_match('/token=(.*)/', $doc->image, $matches);
        $doc->tokenthumb = $matches[1];
        unset($doc->image);
        unset($doc->video);
        unset($doc->anilist);
    }
    $final_result->docs = $docs;
    
    header('Content-Type: application/json');
    echo json_encode($final_result);
}
?>
