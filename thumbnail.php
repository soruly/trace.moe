<?php
require 'vendor/autoload.php';

$thumbdir = 'thumbnail/';
$uuid = uniqid();
$t = floatval($_GET['t']);
$season = rawurldecode($_GET['season']);
$anime = rawurldecode($_GET['anime']);
$file = rawurldecode($_GET['file']);
$filepath = '/mnt/Data/Anime New/'.$season.'/'.$anime.'/'.$file;
$thumbpath = $thumbdir.$uuid.'.jpg';

$ffmpeg = FFMpeg\FFMpeg::create();
$video = $ffmpeg->open($filepath);
$video
  ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($t))
  ->save($thumbpath);

header('Content-type: image/jpeg');

list($width, $height) = getimagesize($thumbpath);
$new_width = 320;
$new_height = 180;

$image_p = imagecreatetruecolor($new_width, $new_height);
$image = imagecreatefromjpeg($thumbpath);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

imagejpeg($image_p, null, 80);

unlink($thumbpath);
?>
