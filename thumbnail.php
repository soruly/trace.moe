<?php
require 'vendor/autoload.php';

header('Content-type: image/jpeg');

$thumbdir = 'thumbnail/';
$uuid = uniqid();
$t = floatval($_GET['t']);
$season = rawurldecode($_GET['season']);
$anime = rawurldecode($_GET['anime']);
$file = rawurldecode($_GET['file']);
$filepath = '/mnt/data/anime_new/'.$season.'/'.$anime.'/'.$file;
$thumbpath = $thumbdir.$uuid.'.jpg';
$new_width = 320;
$new_height = 180;

try {
  $ffmpeg = FFMpeg\FFMpeg::create();
  $video = $ffmpeg->open($filepath);
  $video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($t))
    ->save($thumbpath);

  list($width, $height) = getimagesize($thumbpath);

  $new_height = $height / $width * $new_width;

  $image_p = imagecreatetruecolor($new_width, $new_height);
  $image = imagecreatefromjpeg($thumbpath);
  imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

  imagejpeg($image_p, null, 80);

  unlink($thumbpath);
  imagedestroy($image_p);
} catch (Exception $e) {

  $image_p = imagecreatetruecolor($new_width, $new_height);
  $text_color = imagecolorallocate($image_p, 255, 255, 255);
  imagestring($image_p, 3, 5, 5,  'Preview unavailable', $text_color);
  imagejpeg($image_p, null, 80);
  imagedestroy($image_p);
}
?>
