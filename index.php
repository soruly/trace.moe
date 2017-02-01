<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Webpage">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="description" content="Search Anime by ScreenShot. Search over 500 million images to lookup what anime, which episode, which moment the screenshot is taken from.">
<meta name="keywords" content="Anime Scene Search, Search by image, Anime Image Search, アニメのキャプ画像">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=995, initial-scale=1">
<title>WAIT: What Anime Is This? - Anime Scene Search Engine</title>

<!-- Schema.org markup (Google) -->
<meta itemprop="name" content="WAIT: What Anime Is This?">
<meta itemprop="description" content="Anime Scene Search Engine. Search over 500 million images to lookup what anime, which episode, which moment the screenshot is taken from.">
<meta itemprop="image" content="https://whatanime.ga/favicon128.png">

<!-- Twitter Card markup-->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@soruly">
<meta name="twitter:title" content="WAIT: What Anime Is This?">
<meta name="twitter:description" content="Anime Scene Search Engine. Search over 500 million images to lookup what anime, which episode, which moment the screenshot is taken from.">
<meta name="twitter:creator" content="@soruly">
<!-- Twitter summary card with large image must be at least 280x150px -->
<meta name="twitter:image" content="https://whatanime.ga/favicon128.png">
<meta name="twitter:image:alt" content="Anime Scene Search Engine. Search over 500 million images to lookup what anime, which episode, which moment the screenshot is taken from.">

<!-- Open Graph markup (Facebook, Pinterest) -->
<meta property="og:title" content="WAIT: What Anime Is This?" />
<meta property="og:type" content="article" />
<meta property="og:url" content="https://whatanime.ga" />
<meta property="og:image" content="https://whatanime.ga/favicon128.png" />
<meta property="og:description" content="Anime Scene Search Engine. Search over 500 million images to lookup what anime, which episode, which moment the screenshot is taken from." />
<meta property="og:site_name" content="whatanime.ga" />

<link rel="icon" type="image/png" href="/favicon.png">
<link rel="icon" type="image/png" href="/favicon128.png" sizes="128x128">
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/style.css" rel="stylesheet">
<link href="/index.css" rel="stylesheet">
<link rel="dns-prefetch" href="https://image.whatanime.ga/">
<script src="/recaptcha.js" defer></script>
<script src="/analytics.js" defer></script>
</head>
<body>
<!--<div class="alert alert-warning">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Scheduled Maintenance</strong> A server maintenance will be carried out on 22 Mar, 2016 01:30-09:30am (UTC)
</div>-->
<input id="autoSearch" type="checkbox" style="display: none;">
<img id="originalImage" src="" crossorigin="anonymous" style="display: none;">
<div class="row">
<div class="col-xs-8">
<div id="main">
<div class="noselect">
<div id="loading" class="hidden">
<span class="glyphicon glyphicon-repeat spinning"></span>
</div>
<canvas id="preview" width="640" height="360"></canvas>
<video id="player" style="display:none" volume="1" autoplay></video>
</div>
<form action="" method="get">
<span class="btn btn-default btn-file btn-sm">
Browse a file <input type="file" id="file" name="files[]" />
</span>
<span id="instruction"> / Drag &amp; Drop Anime ScreenShot / Ctrl+V / Enter Image URL</span>
<br>
<input type="text" class="form-control" id="imageURL" placeholder="Image URL" style="margin:5px 0 5px 0">
<div style="text-align: right">
<span id="messageText" style="float:left;line-height:30px"></span>
<label class="radio-inline"><input type="radio" name="fitRadio" id="fitWidthRadio" checked>Fit Width</label>
<label class="radio-inline" style="margin-right:10px"><input type="radio" name="fitRadio" id="fitHeightRadio">Fit Height</label>
<button id="flipBtn" type="button" class="btn btn-default btn-sm" disabled>
<span class="glyphicon glyphicon-unchecked"></span> Flip Image
</button>
<button id="safeBtn" type="button" class="btn btn-default btn-sm">
<span class="glyphicon glyphicon-unchecked"></span> Safe Search
</button>
<button id="searchBtn" type="button" class="btn btn-default btn-sm btn-primary" disabled>
<span class="glyphicon glyphicon-search"></span> Search
</button>
</div>
Please read <a href="/faq">FAQ</a> to understand what can / cannot be searched.<br>
<span style="color:#FF6D6D">Caution: some results may be NSFW (Not Safe for Work).</span><br>
Official WebExtension available on <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp" target="_blank">Chrome</a>, <a href="https://addons.mozilla.org/en-US/firefox/addon/search-anime-by-screenshot/" target="_blank">Firefox</a>, and <a href="https://addons.opera.com/en/extensions/details/search-anime-by-screenshot/" target="_blank">Opera</a>.<br>
Official Telegram Bot available <a href="https://telegram.me/WhatAnimeBot">@WhatAnimeBot</a><br>
</form>
</div>
<div id="info"></div>

<br>
</div>
<div class="col-xs-4">
  <div id="results-list">
    <div id="controls" class="checkbox">
      <label><input type="checkbox" id="autoplay" name="autoplay" checked />AutoPlay</label>
      <label><input type="checkbox" id="loop" name="loop" />Loop</label>
      <label><input type="checkbox" id="mute" name="mute" />Mute</label>
    </div>
    <div class="g-recaptcha hidden" data-sitekey="6LdluhITAAAAAD4-wl-hL-gR6gxesY6b4_SZew7v" data-callback="recaptcha_success" data-size="normal"></div>
    <ul id="results" class="nav nav-pills nav-stacked"></ul>
  </div>
</div>

<a href="https://github.com/soruly/whatanime.ga" class="github-corner" aria-label="View source on Github">
<svg width="80" height="80" viewBox="0 0 250 250" style="fill:#151513; color:#fff; position: absolute; top: 0; border: 0; right: 0;" aria-hidden="true"><path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path><path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path><path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path></svg>
</a>
<style>.github-corner:hover .octo-arm{animation:octocat-wave 560ms ease-in-out}@keyframes octocat-wave{0%,100%{transform:rotate(0)}20%,60%{transform:rotate(-25deg)}40%,80%{transform:rotate(10deg)}}@media (max-width:500px){.github-corner:hover .octo-arm{animation:none}.github-corner .octo-arm{animation:octocat-wave 560ms ease-in-out}}</style>

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
<script src="/jquery-2.1.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/jquery.html5uploader.min.js"></script>
<script src="/index.js"></script>
<?php
if(isset($_GET["url"])){
echo '<script>
document.querySelector("#autoSearch").checked = true;
document.querySelector("#messageText").classList.remove("error");
document.querySelector("#messageText").classList.remove("success");
document.querySelector("#messageText").innerHTML = \'<span class="glyphicon glyphicon-repeat spinning"></span>\';
originalImage.src = "https://image.whatanime.ga/imgproxy?url='.str_replace(' ','%20',rawurldecode($_GET["url"])).'";
document.querySelector("#imageURL").value = "'.str_replace(' ','%20',rawurldecode($_GET["url"])).'";
</script>';
}
?>
</body>
</html>
