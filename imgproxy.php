<?php
header("HTTP/1.0 404 Not Found");
if( isset( $_SERVER['HTTP_REFERER'] ) && ( strpos($_SERVER['HTTP_REFERER'],'https://whatanime.ga/') == 0 ) ){
    //request from original site, continue
}
else{
    header("HTTP/1.0 404 Not Found");
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

$siteKey = '6LdluhITAAAAAD4-wl-hL-gR6gxesY6b4_SZew7v';
$secret = '6LdluhITAAAAAPS-b-k5VBjRjaMm0xBOeyPwDrEL';
$lang = 'en';

if($redis->exists($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $quota = intval($redis->get($_SERVER['HTTP_X_FORWARDED_FOR']));
    if($quota < 1){
        exit(header("HTTP/1.0 429 Too Many Requests"));
    }
    else{
    	$quota--;
    	$expire = $redis->ttl($_SERVER['HTTP_X_FORWARDED_FOR']);
    	$redis->set($_SERVER['HTTP_X_FORWARDED_FOR'], $quota);
    	$redis->expire($_SERVER['HTTP_X_FORWARDED_FOR'], $expire);
    }
}
else{
    $quota = 60;
    $redis->set($_SERVER['HTTP_X_FORWARDED_FOR'], $quota);
    $redis->expire($_SERVER['HTTP_X_FORWARDED_FOR'], 3600);
}

if(isset($_GET["url"])){
    ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36');
	$url = base64_decode($_GET['url']);
	if (substr($url, 0, 7) == "http://" || substr($url, 0, 8) == "https://") { 

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_REFERER, parse_url($url, PHP_URL_SCHEME).'://'.parse_url($url, PHP_URL_HOST).'/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		$response = curl_exec($ch);
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		curl_close($ch);

		list($header, $body) = explode("\r\n\r\n", $response, 2);

		switch ($content_type) {
		    case "image/gif":
		        header('Content-Type: image/gif');
		        echo $body;
		        break;
		    case "image/png":
		        header('Content-Type: image/png');
		        echo $body;
		        break;
		    case "image/bmp":
		        header('Content-Type: image/bmp');
		        echo $body;
		        break;
		    case "image/webp":
		        header('Content-Type: image/webp');
		        echo $body;
		        break;
		    case "image/jpeg":
		        header('Content-Type: image/jpeg');
		        echo $body;
		        break;
	        default:
	        	header('Content-Type: image/jpeg');
		        echo $body;
	        	//header("HTTP/1.0 404 Not Found");
	        	break;
		}
	}
}
?>
