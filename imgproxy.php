<?php

header("access-control-allow-origin: https://whatanime.ga");

if(isset($_GET["url"])){
	ini_set('user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3011.0 Safari/537.36');
	$url = str_replace(' ','%20',rawurldecode($_GET["url"]));
	if (substr($url, 0, 7) == "http://" || substr($url, 0, 8) == "https://") {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_REFERER, parse_url($url, PHP_URL_SCHEME).'://'.parse_url($url, PHP_URL_HOST).'/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);
		try{
			$image = new Imagick();
			$image->readImageBlob($response);
			$image->setBackgroundColor('#ffffff');
			$image = $image->flattenImages();
			$image = $image->coalesceImages();
			if($image->getImageWidth() > 640)
				$image->scaleImage(640,0);
			$image->setImageFormat('jpeg');
			$image->setImageCompressionQuality(80);
			header("access-control-allow-origin: https://whatanime.ga");
			header( "Content-Type: image/jpg" );
			echo $image;
		}
		catch(Exception $e){
			header("HTTP/1.0 404 Not Found");
		}
	}
}
?>
