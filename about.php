<?php
ini_set("display_errors", 0);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://192.168.2.11:8983/solr/anime_cl/admin/luke?wt=json");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($curl);
$result = json_decode($res);
curl_close($curl);
$lastModified = date_timestamp_get(date_create($result->index->lastModified));

function humanTiming($time){
    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
}
$numDocs = $result->index->numDocs;
$numDocsMillion = floor($numDocs / 1000000);

$vmTouchFile = file_get_contents('./vmtouch.log');
$vmTouch = explode(" ",explode("\n", $vmTouchFile)[2]);

$to_percent = function($load_average){
  return round(floatval($load_average) / 8 * 100, 1) ."%";
};
$loadAverage = implode(", ",array_map($to_percent, explode(", ",explode("load average: ",exec("uptime"))[1])));

$recentFile = str_replace('.xml','',shell_exec('find /mnt/Data/Anime\ Hash/ -type f -mmin -180 -name "*.xml" -exec basename "{}" \;'));

?><!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WAIT: What Anime Is This? - About</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="icon" type="image/png" href="/favicon128.png" sizes="128x128">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/style.css" rel="stylesheet">
<script src="/analytics.js" defer></script>
  </head>
  <body>
  <?php echo '<div style="display:none">'.$output."</div>"; ?>
<nav class="navbar header">
<div class="container">
<ul class="nav navbar-nav">
<li><a href="/">Home</a></li>
<li><a href="/about" class="active">About</a></li>
<li><a href="/changelog">Changelog</a></li>
<li><a href="/faq">FAQ</a></li>
<li><a href="/terms">Terms</a></li>
</ul>
</div>
</nav>
        <div class="container">
      <div class="page-header">
        <h1>About</h1>
      </div>
      <img src="/favicon128.png" alt="" style="display:none" />
<p>Life is too short to answer all the "What is the anime?" questions. Let computers do that for you.</p>
<p>
whatanime.ga is a test-of-concept prototype search engine that helps users trace back the original anime by screenshot. 
It searches over 15600 hours of anime and find the best matching scene. 
It tells you what anime it is, from which episode and the time that scene appears. 
Since the search result may not be accurate, it provides a few seconds of preview for verification. 
</p>
<p>
There has been a lot of anime screencaps and GIFs spreading around the internet, but very few of them mention the source. While those online platforms are gaining popularity, whatanime.ga respects the original producers and staffs by showing interested anime fans what the original source is. This search engine encourages users to give credits to the original creater / owner before they share stuff online.
</p>
<p>
This website is non-profit making. There is no pro/premium features at all.
This website is not intended for watching anime. The server has effective measures to forbid users to access the original video beyond the preview limit. I would like to redirect users to somewhere they can watch that anime legally, if possible.
</p>
<p>
Most Anime since 2000 are indexed, but some are excluded (see FAQ).
No Doujin work, no derived art work are indexed. The system only analyzes officially published anime. 
If you wish to search artwork / wallpapers, try to use <a href="https://saucenao.com/">SauceNAO</a> and <a href="https://iqdb.org/">iqdb.org</a>
</p>
<div class="page-header">
<h3>Official WebExtension</h3>
</div>
<p>WebExtension available for <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp">Chrome</a>, <a href="https://addons.mozilla.org/en-US/firefox/addon/search-anime-by-screenshot/">Firefox</a>, or <a href="https://addons.opera.com/en/extensions/details/search-anime-by-screenshot/">Opera</a> to search.</p>
<div class="page-header">
<h3>Official Telegram Bot</h3>
</div>
<p>Official Telegram Bot available <a href="https://telegram.me/WhatAnimeBot">@WhatAnimeBot</a></p>
<div class="page-header">
<h3>Official API (Beta)</h3>
</div>
<p>Official API Docs available at <a href="https://soruly.github.io/whatanime.ga/#/">GitHub</a></p>
<div class="page-header">
<h3>Go-talk presentation</h3>
</div>
<p><a href="https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga.slide">Go-talk presentation on 27 May 2016</a></p>
<div class="page-header">
<h3>System Status</h3>
</div>
<p><?php if($loadAverage) echo "System load average in 1, 5, 15 minutes: ".$loadAverage ?></p>
<p><?php if($vmTouch) echo $vmTouch[6]."B (".$vmTouch[8].") index is cached in RAM, the rest are in SSD."; ?></p>
<p>This database automatically index most airing anime in a few hours after broadcast. </p>
<p><?php echo 'Last Database Index update: '.humanTiming($lastModified).' ago with '.$numDocsMillion.' Million analyzed frames.<br>'; ?></p>
<p><?php if($recentFile) echo "Recently indexed files: (last 3 hours) <pre>".$recentFile."</pre>"; ?></p>
<p>Full Database Dump 2016-10 (20.4GB)<br>
<a href="magnet:?xt=urn:btih:ec1e85c19cadfbf4e83e9d59fccf3b8abedd49db&dn=whatanime.ga%20database%20dump%202016-10&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Fopentor.org%3A2710&tr=udp%3A%2F%2Ftracker.ccc.de%3A80&tr=udp%3A%2F%2Ftracker.blackunicorn.xyz%3A6969&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969">magnet:?xt=urn:btih:ec1e85c19cadfbf4e83e9d59fccf3b8abedd49db</a>
</p>
<div class="page-header">
<h3>Contact</h3>
</div>
<p>If you have any feedback, suggestions or anything else, you may contact <a href="mailto:help@whatanime.ga">help@whatanime.ga</a>.</p>
<p>Follow the development of whatanime.ga and learn more about the underlying technologies at the <a href="https://plus.google.com/communities/115025102250573417080">Google+ Community</a> or <a href="https://www.facebook.com/whatanime.ga/">Facebook Page</a>.</p>

<div class="page-header">
<h3>Credit</h3>
</div>
<p>
Dr. Mathias Lux (LIRE Project)<br>
<a href="http://www.lire-project.net/">http://www.lire-project.net/</a><br>
Lux Mathias, Savvas A. Chatzichristofis. Lire: Lucene Image Retrieval – An Extensible Java CBIR Library. In proceedings of the 16th ACM International Conference on Multimedia, pp. 1085-1088, Vancouver, Canada, 2008<br>
<a href="http://www.morganclaypool.com/doi/abs/10.2200/S00468ED1V01Y201301ICR025">Visual Information Retrieval with Java and LIRE</a><br>
<br>
ANILIST<br>
Josh (Anilist developer) and Anilist administration team<br>
<a href="https://anilist.co/">https://anilist.co/</a><br>
<br>
And of cause, contributions and support from all anime lovers!<br>
<br>
<a href="https://www.patreon.com/soruly">Support whatanime.ga on Patreon.</a><br>
<br>
</p>

</div>
<footer class="footer">
<div class="container">
<ol class="breadcrumb">
<li><a href="/">Home</a></li>
<li><a href="/about">About</a></li>
<li><a href="/changelog">Changelog</a></li>
<li><a href="/faq">FAQ</a></li>
<li><a href="/terms" class="active">Terms</a></li>
</ol>
      </div>
    </footer>
  </body>
</html>
