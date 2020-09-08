<?php
require 'vendor/autoload.php';

if (!isset($_GET['t']) || !isset($_GET['anilist_id']) || !isset($_GET['file'])) {
  http_response_code(400);
  exit('400 Bad Request');
}

$thumbdir = 'thumbnail/';
$uuid = uniqid();
$t = floatval($_GET['t']);
$anilistID = rawurldecode($_GET['anilist_id']);
$file = rawurldecode($_GET['file']);
$filepath = '/mnt/data/anilist/'.$anilistID.'/'.$file;
$thumbpath = $thumbdir.$uuid.'.jpg';

if (!file_exists($filepath)) {
  http_response_code(404);
  exit('404 Not Found');
}

$new_width = 320;
$new_height = 180;


try {

  $ffmpeg = FFMpeg\FFMpeg::create([
     'ffmpeg.binaries' => '/usr/bin/avconv',
     'ffmpeg.binaries' => '/usr/bin/ffmpeg',
     'ffprobe.binaries' => '/usr/bin/avprobe',
     'ffprobe.binaries' => '/usr/bin/ffprobe'
  ]);
  $video = $ffmpeg->open($filepath);
  $video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($t))
    ->save($thumbpath);

  if (!file_exists($thumbpath)) {
    http_response_code(500);
    exit('500 Failed to generate preview');
  }
  
  list($width, $height) = getimagesize($thumbpath);

  $new_height = $height / $width * $new_width;

  $image_p = imagecreatetruecolor($new_width, $new_height);
  $image = imagecreatefromjpeg($thumbpath);
  imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

  header('Content-type: image/jpeg');
  imagejpeg($image_p, null, 80);

  unlink($thumbpath);
  imagedestroy($image_p);
} catch (Exception $e) {

  $image_p = imagecreatetruecolor($new_width, $new_height);
  $text_color = imagecolorallocate($image_p, 255, 255, 255);
  imagestring($image_p, 3, 5, 5,  'Preview unavailable', $text_color);
  header('Content-type: image/jpeg');
  imagejpeg($image_p, null, 80);
  imagedestroy($image_p);
}
?>
