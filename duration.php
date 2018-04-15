<?php
require 'vendor/autoload.php';

// header('Content-type: image/jpeg');

$anilistID = rawurldecode($_GET['anilist_id']);
$file = rawurldecode($_GET['file']);
$filepath = '/mnt/data/anilist/'.$anilistID.'/'.$file;

try {

  $ffprobe = FFMpeg\FFProbe::create([
     'ffmpeg.binaries' => '/usr/bin/avconv',
     'ffmpeg.binaries' => '/usr/bin/ffmpeg',
     'ffprobe.binaries' => '/usr/bin/avprobe',
     'ffprobe.binaries' => '/usr/bin/ffprobe'
  ]);
  echo floatval($ffprobe->format($filepath)->get('duration'));
} catch (Exception $e) {
  echo $e;
}
?>
