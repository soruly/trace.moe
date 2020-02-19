<?php
ini_set("display_errors", 0);

$thumbdir = 'clip/';
$uuid = uniqid();
$start = floatval($_GET['t']) - 0.9;
$duration = 3;
$anilistID = rawurldecode($_GET['anilist_id']);
$season = rawurldecode($_GET['season']);  // deprecated
$anime = rawurldecode($_GET['anime']);  // deprecated
$file = rawurldecode($_GET['file']);
$filepath = str_replace('`', '\`', '/mnt/data/anilist/'.$anilistID.'/'.$file);
if ($season && $anime) {  // deprecated
  $filepath = str_replace('`', '\`', '/mnt/data/anime_new/'.$season.'/'.$anime.'/'.$file);
}
$thumbpath = $thumbdir.$uuid.'.mp4';
$mute = isset($_GET['mute']) ? "-an" : "";

if(file_exists($filepath)){
    exec("ffmpeg -y -ss ".$start." -i \"$filepath\" -to ".$duration." -vf scale=640:-1 -c:v libx264 ".$mute." -preset fast ".$thumbpath);
}

header('Content-type: video/mp4');
readfile($thumbpath);
unlink($thumbpath);
?>
