<?php
ini_set("display_errors", 0);

$thumbdir = 'clip/';
$uuid = uniqid();
$start = floatval($_GET['t']) - 0.5;
$duration = 2.5;
$season = rawurldecode($_GET['season']);
$anime = rawurldecode($_GET['anime']);
$file = rawurldecode($_GET['file']);
$filepath = str_replace('`', '\`', '/mnt/Data/Anime New/'.$season.'/'.$anime.'/'.$file);
$thumbpath = $thumbdir.$uuid.'.mp4';

if(file_exists($filepath)){
    exec("ffmpeg -y -ss ".$start." -i \"$filepath\" -to ".$duration." -vf scale=640:-1 -c:v libx264 -preset fast ".$thumbpath);
}

header('Content-type: video/mp4');
readfile($thumbpath);
unlink($thumbpath);
?>