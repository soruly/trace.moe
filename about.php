<?php
header("Link: </css/style.css>; rel=preload; as=style", false);
header("Link: </css/bootstrap.min.css>; rel=preload; as=style", false);
header("Link: </js/analytics.js>; rel=preload; as=script", false);

ini_set("display_errors", 0);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://127.0.0.1:8988/solr/admin/cores?wt=json");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($curl);
$result = json_decode($res);
curl_close($curl);

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
$numDocs = 0;
$sizeInBytes = 0;
$lastModified = 0;
foreach ($result->status as $core) {
  $numDocs += $core->index->numDocs;
  $sizeInBytes += $core->index->sizeInBytes;
  if(strtotime($core->index->lastModified) > $lastModified){
    $lastModified = strtotime($core->index->lastModified);
  }
}
$numDocsMillion = floor($numDocs / 1000000);
$sizeInGB = floor($sizeInBytes / 1073741824);

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="description" content="Search Anime by ScreenShot. Lookup the exact moment and the episode.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WAIT: What Anime Is This? - About</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="icon" type="image/png" href="/favicon128.png" sizes="128x128">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script src="/js/analytics.js" defer></script>
  </head>
  <body>
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
trace.moe is a test-of-concept prototype search engine that helps users trace back the original anime by screenshot. 
It search in ~30000 hours of anime and find the best matching scene. 
It tells you what anime it is, from which episode and the time that scene appears. 
Since the search result may not be accurate, it provides a few seconds of preview for verification. 
</p>
<p>
There has been a lot of anime screencaps and GIFs spreading around the internet, but very few of them mention the source. While those online platforms are gaining popularity, trace.moe respects the original producers and staffs by showing interested anime fans what the original source is. This search engine encourages users to give credits to the original creators / owners before they share stuff online.
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
<h3>WebExtension</h3>
</div>
<p>WebExtension available for <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp">Chrome</a>, <a href="https://addons.mozilla.org/en-US/firefox/addon/search-anime-by-screenshot/">Firefox</a>, or <a href="https://addons.opera.com/en/extensions/details/search-anime-by-screenshot/">Opera</a> to search.</p>
<p>Source code and user guide on Github:<br><a href="https://github.com/soruly/trace.moe-WebExtension">https://github.com/soruly/trace.moe-WebExtension</a></p>
<div class="page-header">
<h3>Telegram Bot</h3>
</div>
<p>Telegram Bot available <a href="https://telegram.me/WhatAnimeBot">@WhatAnimeBot</a></p>
<p>Source code and user guide on Github:<br><a href="https://github.com/soruly/trace.moe-telegram-bot">https://github.com/soruly/trace.moe-telegram-bot</a>
<div class="page-header">
<h3>Official API (Beta)</h3>
</div>
<p>Official API Docs available at <a href="https://soruly.github.io/trace.moe/#/">GitHub</a></p>
<div class="page-header">
<h3>Mobile Apps</h3>
</div>
<p>
WhatAnime by Andrée Torres<br>
<a href="https://play.google.com/store/apps/details?id=com.maddog05.whatanime">https://play.google.com/store/apps/details?id=com.maddog05.whatanime</a><br>
Source: <a href="https://github.com/maddog05/whatanime-android">https://github.com/maddog05/whatanime-android</a><br>
<br>
WhatAnime - 以图搜番 by Mystery0 (Simplified Chinese)<br>
<a href="https://play.google.com/store/apps/details?id=pw.janyo.whatanime">https://play.google.com/store/apps/details?id=pw.janyo.whatanime</a><br>
Source: <a href="https://github.com/JanYoStudio/WhatAnime">https://github.com/JanYoStudio/WhatAnime</a><br>
</p>
<div class="page-header">
<h3>Presentation slides</h3>
</div>
<p><a href="https://github.com/soruly/slides">https://github.com/soruly/slides</a></p>
<div class="page-header">
<h3>System Status</h3>
</div>
<p>System status page: <a href="https://status.trace.moe">https://status.trace.moe</a> (Powered by UptimeRobot)</p>
<p><?php echo 'Last Database Index update: '.humanTiming($lastModified).' ago with '.$numDocsMillion.' Million analyzed frames. ('.$sizeInGB.' GB)<br>'; ?></p>
<p>This database automatically index most airing anime in a few hours after broadcast.<br>You may subscribe to the updates on Telegram <a href="https://t.me/whatanimeupdates">@whatanimeupdates</a></p>
<p></p>
<a href="https://nyaa.si/download/1292174.torrent">Full Database Dump 2020-10 (17.4GB)</a><br>
<a href="magnet:?xt=urn:btih:45900fa256d7773c30accf9ec54fac469d1b0500&dn=trace.moe%20database%20dump%202020-10&tr=http%3A%2F%2Fnyaa.tracker.wf%3A7777%2Fannounce&tr=udp%3A%2F%2Fopen.stealth.si%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969%2Fannounce&tr=udp%3A%2F%2Fexodus.desync.com%3A6969%2Fannounce">magnet:?xt=urn:btih:45900fa256d7773c30accf9ec54fac469d1b0500</a>
</p>
<div class="page-header">
<h3>Contact</h3>
</div>
<p>If you have any feedback, suggestions or anything else, you may reach the author on Telegram <a href="https://t.me/soruly">@soruly</a> or <a href="https://discord.gg/K9jn6Kj">Discord</a>.</p>
<p>Follow the development of trace.moe and learn more about the underlying technologies on <a href="https://github.com/soruly/slides">GitHub</a> and <a href="https://www.patreon.com/soruly">Patreon page</a>.</p>

<div class="page-header">
<h3>Credit</h3>
</div>
<p>
<h4>Dr. Mathias Lux (<a href="http://www.lire-project.net/">LIRE Project</a>)</h4>
<small>Lux Mathias, Savvas A. Chatzichristofis. Lire: Lucene Image Retrieval – An Extensible Java CBIR Library. In proceedings of the 16th ACM International Conference on Multimedia, pp. 1085-1088, Vancouver, Canada, 2008 <a href="http://www.morganclaypool.com/doi/abs/10.2200/S00468ED1V01Y201301ICR025">Visual Information Retrieval with Java and LIRE</a></small><br>
<br>
<h4>Josh (<a href="https://anilist.co/">Anilist</a>) and Anilist team</h4>
</p>
<div class="page-header">
<h3 id="donate">Donate</h3>
</div>
<p>
<div>
  <a href="https://www.paypal.me/soruly"><img src="img/donate-with-paypal.png" alt="Donate with PayPal" style="display:inline-block;max-height:50px;"></a> or 
  <a href="https://www.patreon.com/soruly"><img src="img/become_a_patron_button.png" alt="Become a Patron!" style="display:inline-block;max-height:50px"></a>
</div>
<br>
Former and Current Supporters:<br>
<ul>
  <li><a href="https://chenxublog.com">chenxuuu</a></li>
  <li><a href="http://desmonding.me/">Desmond</a></li>
  <li><a href="http://imvery.moe/">FangzhouL</a></li>
  <li><a href="https://fym.moe/">FiveYellowMice</a></li>
  <li>Snadzies</li>
  <li><a href="https://taw.moe">thatanimeweirdo</a></li>
  <li>WelkinWill</li>
  <li><a href="https://twitter.com/yuriks">yuriks</a></li>
  <li>...and dozons of anonymous donators</li>
</ul>
<br>
<h4>And of cause, contributions and support from all anime lovers!</h4>
<br>
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
