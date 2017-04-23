<?php
header("access-control-allow-origin: https://whatanime.ga");

if(isset($_GET["url"])){
  ini_set('user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3011.0 Safari/537.36');
  $url = str_replace(' ','%20',rawurldecode($_GET["url"]));
  if (substr($url, 0, 7) == "http://" || substr($url, 0, 8) == "https://") {

    header("Content-Type: image/jpg");
    try {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_REFERER, parse_url($url, PHP_URL_SCHEME).'://'.parse_url($url, PHP_URL_HOST).'/');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
      $response = curl_exec($ch);
      curl_close($ch);

      $image = imagecreatefromstring($response);
      if($image === false) throw new Exception();
      list($width, $height) = getimagesizefromstring($response);
      $new_width = $width > 640 ? 640 : $width;
      $new_height = $height / $width * $new_width;
      $thumb = imagecreatetruecolor($new_width, $new_height);
      imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
      imagejpeg($thumb, null, 80);
    }
    catch(Exception $e){
      $image = imagecreatetruecolor(640, 360);
      $text_color = imagecolorallocate($image, 255, 255, 255);
      imagestring($image, 3, 5, 5,  'Failed to load image URL', $text_color);
      imagestring($image, 3, 5, 25,  'The image maybe a private URL that is not publicly accessible.', $text_color)
        imagejpeg($image, null, 80);
      imagedestroy($image);
    }
  }
}
?>

