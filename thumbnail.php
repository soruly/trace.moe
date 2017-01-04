<?php
$thumbdir = 'thumbnail/';
$uuid = uniqid();
$t = floatval($_GET['t']);
$season = rawurldecode($_GET['season']);
$anime = rawurldecode($_GET['anime']);
$file = rawurldecode($_GET['file']);
$filepath = str_replace('`', '\`', '/mnt/Data/Anime New/'.$season.'/'.$anime.'/'.$file);
exec("ffmpeg -y -ss ".$t." -i \"$filepath\" -vframes 1 -vf scale=300:-1 -f image2 thumbnail/".$uuid.".jpg");
header('Content-type: image/jpeg');
readfile($thumbdir.$uuid.'.jpg');
unlink($thumbdir.$uuid.'.jpg');
?>