<?php
ini_set("display_errors", 0);

if (!isset($_GET['t']) || !isset($_GET['anilist_id']) || !isset($_GET['file'])) {
  http_response_code(400);
  exit('400 Bad Request');
}

$thumbdir = 'clip/';
$uuid = uniqid();
$start = floatval($_GET['t']) - 0.9;
$duration = 3;
$anilistID = rawurldecode($_GET['anilist_id']);
$file = rawurldecode($_GET['file']);
$filepath = str_replace('`', '\`', '/mnt/data/anilist/'.$anilistID.'/'.$file);
$thumbpath = $thumbdir.$uuid.'.mp4';
$mute = isset($_GET['mute']) ? "-an" : "";

if (!file_exists($filepath)) {
  http_response_code(404);
  exit('404 Not Found');
}

exec("ffmpeg -y -ss ".$start." -i \"$filepath\" -to ".$duration." -vf scale=640:-1 -c:v libx264 ".$mute." -preset fast ".$thumbpath);

if (!file_exists($thumbpath)) {
  http_response_code(500);
  exit('500 Failed to generate preview');
}

header('Content-type: video/mp4');
readfile($thumbpath);
unlink($thumbpath);
?>
