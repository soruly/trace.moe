<?php
ini_set("display_errors", 0);
header('Content-Type: application/json');
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){
    //request from AJAX, continue
//}
//else if( isset( $_SERVER['HTTP_REFERER'] ) && ( $_SERVER['HTTP_REFERER'] == 'https://whatanime.ga/' ) ){
    //request from original site, continue
}
else{
    header("HTTP/1.0 404 Not Found");
}

if(isset($_GET['season']) && isset($_GET['anime'])){
$regex = "/[\\+\\-\\=\\&\\|\\ \\!\\(\\)\\{\\}\\[\\]\\^\\\"\\~\\*\\<\\>\\?\\:\\\\\\/]/";
$season = preg_replace($regex, addslashes('\\$0'), urldecode($_GET['season']));
$anime = preg_replace($regex, addslashes('\\$0'), urldecode($_GET['anime']));

$request = array(
"size" => 1,
"query" => array(
    "bool" => array(
        "must" => array(
            array("query_string" => array("default_field" => "directory", "query" => $season)),
            array("query_string" => array("default_field" => "sub_directory", "query" => $anime))
        )
    )
)
);
$payload = json_encode($request);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1:9200/karikari/anime/_search");
curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($curl);
$result = json_decode($res);
foreach($result->hits->hits as $hit){
    $hits[] = $hit->_source;
}
curl_close($curl);
echo(json_encode($hits));
}
?>
