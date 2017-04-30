<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>WAIT: What Anime Is This? - Changelog</title>
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
<li><a href="/about">About</a></li>
<li><a href="/changelog" class="active">Changelog</a></li>
<li><a href="/faq">FAQ</a></li>
<li><a href="/terms">Terms</a></li>
</ul>
</div>
</nav>
<div class="container">
<div class="page-header"><h1>Changelog</h1></div>

<h3>Search changes</h3>
<h6>30 Apr 2017</h6>
<p>You can now search scenes with any aspect ratio. Thumbnail preview also respect aspect ratio now. Recaptcha is removed, you must wait up to 10 minutes once you have reached search quota limit (20 search per 10 minutes). Homepage code has been re-written, the webpage now loads faster. And a new loading animation was added.</p>

<h3>Search for more results</h3>
<h6>23 Apr 2017</h6>
<p>You can now keep searching the database for more results. Previously, the search would stop when it has found any result > 90% similarity. Now keep searching to discover more results with even higher similarity!</p>

<h3>Search in specific season</h3>
<h6>18 Apr 2017</h6>
<p>You can now select a particular year / season to search. If you like this project, feel free to <a href="https://www.patreon.com/soruly">Support whatanime.ga on Patreon.</a></p>

<h3>System maintenance</h3>
<h6>15 Apr 2017</h6>
<p>Server upgrade and cleanup was completed on 15 Apr 2017. An additional hard drive and new network adapter has been installed.</p>

<h3>System status page</h3>
<h6>14 Apr 2017</h6>
<p>You can now see the system status in <a href="https://status.whatanime.ga">https://status.whatanime.ga</a> (Powered by UptimeRobot).</p>

<h3>Partial service interruption</h3>
<h6>23 Feb 2017</h6>
<p>Anime info panel was not showing since Feb 21 21:13 UTC , the service has been restored on Feb 23 03:34 UTC.</p>

<h3>Image proxy relocated</h3>
<h6>21 Feb 2017</h6>
<p>The image proxy server has been moved from Singapore (Digital Ocean) to Tokyo (Linode). It may affect loading times of images from ?url= params.</p>

<h3>Video preview for Telegram Bot</h3>
<h6>4 Feb 2017</h6>
<p>The <a href="https://telegram.me/WhatAnimeBot">Telegram Bot</a> will now sends you a video preview.</p>

<h3>Official API released</h3>
<h6>17 Jan 2017</h6>
<p>The official API is now open for testing. Interested developers may read the page on <a href="https://soruly.github.io/whatanime.ga/">GitHub Pages</a>.</p>

<h3>WebExtension updated</h3>
<h6>16 Nov 2016</h6>
<p>The image extraction method of WebExtension has changed. This would be able to fix some issues on grabbing the correct image on webpage to search. Now the Extension also supports Microsoft Edge. You may try loading the zip from <a href="https://github.com/soruly/whatanime.ga-WebExtension/releases">GitHub</a> by enabling extension developer features in about:flags.</p>

<h3>Enabled autoplay on mobile</h3>
<h6>11 Nov 2016</h6>
<p>Now mobile devies will mute and autoplay the video preview.</p>

<h3>Added URL params for playback options</h3>
<h6>29 Oct 2016</h6>
<p>You can now use URL params to control playback options, for example:<pre>https://whatanime.ga/?autoplay=0&loop&mute=1&url=</pre></p>

<h3>Improved performance</h3>
<h6>23 Oct 2016</h6>
<p>Reduced search result candidates from 10 Million to 3 Million. This would reduce accuracy but greatly improves performance.</p>

<h3>Added links to database dump</h3>
<h6>5 Oct 2016</h6>
<p>You can now download a complete dump of the database.</p>

<h3>Added more server status</h3>
<h6>1 Oct 2016</h6>
<p>You can see server load and recently indexed files in /about</p>

<h3>Adding Raw Anime</h3>
<h6>1 Oct 2016</h6>
<p>The database has started indexing raw anime from now on.</p>

<h3>Added Telegram Bot</h3>
<h6>14 Sep 2016</h6>
<p>This telegram bot can tell you where an anime screenshot is taken from. Just send / forward an image to <a href="https://telegram.me/WhatAnimeBot">https://telegram.me/WhatAnimeBot</a> .</p>

<h3>WebExtension Changes</h3>
<h6>14 Aug 2016</h6>
<p>The ?auto url param is no longer used. Now it would always automatically search.</p>
<p>WebExtension has updated. Now it would copy and paste using dataURL in background. It allows searching images from pages that's not publicly available such as Facebook Feeds. It also supports searching from HTML5 videos using the frame extracted at the moment you click it. Now extensions no longer use the ?url to send search images.</p>

<h3>WebExtension for Chrome, Firefox and Opera</h3>
<h6>1 July 2016</h6>
<p>
<a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp">Chrome Extension</a>, 
<a href="https://addons.mozilla.org/en-US/firefox/addon/search-anime-by-screenshot/">Firefox Add-on</a>, 
<a href="https://addons.opera.com/en/extensions/details/search-anime-by-screenshot/">Opera Add-on</a> has been relased.<br>
Source code available on <a href="https://github.com/soruly/whatanime.ga-WebExtension">GitHub</a>
</p>

<h3>Improved anime title language</h3>
<h6>30 Jun 2016</h6>
<p>It now shows anime titles according to users' browser language.</p>

<h3>Added loading icon</h3>
<h6>30 Jun 2016</h6>
<p>It now shows a loading icon (instead of blurring) while searching. Also display a loading icon when the video preview is loading.</p>

<h3>Added preview thumbnails</h3>
<h6>30 May 2016</h6>
<p>The thumbnail may not be at the exact moment, since the seeking is not very accurate. Play the preview to see if it's what you are looking for.</p>

<h3>Fixed some bugs in URL loading</h3>
<h6>6 May 2016</h6>
<p>In case the image cannot be loaded, upload the image from file or copy image itself (not URL) then Ctrl+V</p>

<h3>Speed up image load from URL</h3>
<h6>23 Apr 2016</h6>
<p>Images load from URL would be compressed. This would speed up loading GIF and large images from URL.</p>

<h3>Performance Tweaks</h3>
<h6>18 Apr 2016</h6>
<p>Server will now cache some search results. Search results would be cached for 5-30 minutes. The better the search results, the longer the results would be cached.</p>

<h3>Updated Chrome Extension</h3>
<h6>8 Apr 2016</h6>
<p>Once the image completes loading, it would search automatically. You can change the setting in Chrome Extension. Also see how you can search in Firefox in <a href="/faq">FAQ</a>.</p>

<h3>Added index cache status</h3>
<h6>7 Apr 2016</h6>
<p>You can now see how much data is cached in RAM from About page. The higher the percentage faster the search. It usually stays around 33% due to limited RAM.</p>

<h3>Incorrect timestamp issue identified</h3>
<h6>6 Apr 2016</h6>
<p>There has been some incorrect timestamp in search results due to image analyze scripts parsing outputs of ffmpeg incorrectly. The script has been updated now. From now on new animes should have a correct timestamp, while it would take at about two months to fix already indexed animes. The new script is also 33% faster when indexing anime.</p>

<h3>Fixes some image editing issue</h3>
<h6>10 Mar 2016</h6>
<p>Image would sometimes gone black when clicking fit / flip button. Now fixed.</p>

<h3>Added Fit Width / Height option</h3>
<h6>6 Mar 2016</h6>
<p>You can now choose to Fit Width / Height for your search image. Also fixed some flickering issue on previews.</p>

<h3>Fixed some Image URL issue</h3>
<h6>3 Mar 2016</h6>
<p>Fixed some cross-site image URL linking issue. Most image URL should load now.</p>

<h3>New Anime Info Panel UI</h3>
<h6>1 Mar 2016</h6>
<p>A better layout for more Anime information.</p>
<h3>Added Image URL Option</h3>
<h6>1 Mar 2016</h6>
<p>You can now search by Image URL</p>
<h3>Added Safe Search Option</h3>
<h6>28 Feb 2016</h6>
<p>The Safe Search Option can hide most Hentai Anime from search result. But you should aware that some regular season Animes can still be obscene. (NSFW)</p>
<h3>Try the Chrome Extension</h3>
<h6>28 Feb 2016</h6>
<p>Now you can use the <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp">Chrome Extension</a> to search.</p>
<h3>Added some Heitai Anime</h3>
<h6>28 Feb 2016</h6>
<p>About 168 Heitai Anime series has been added.</p>
<h3>Added sample screenshot in FAQ</h3>
<h6>28 Feb 2016</h6>
<p>To help users to understand how the search engine works, we have added some good and bad screenshots in <a href="/faq">FAQ</a>.</p>
<h3>Performance Improvement</h3>
<h6>21 Feb 2016</h6>
<p>Improved caching method to warmup cold data.</p>
<h3>More fixes</h3>
<h6>20 Feb 2016</h6>
<p>Database has been cleaned up and reloaded. This should fix most video previews. Fixed some anime titles still being null.</p>
<h3>Bug Fix</h3>
<h6>17 Feb 2016</h6>
<p>Fixed the empty search result. The issue has been resolved. A large number of files has been relocated, video preview may be missing for some search results.</p>
<h3>Improved Performance</h3>
<h6>01 Jan 2016</h6>
<p>Increased cache size to improve performance when the server has been idle for a long time.</p>
<h3>Added a Flip Button</h3>
<h6>31 Dec 2015</h6>
<p>You may now flip the image before searching. If you can't find a match, try to flip your image and search again. (Especially useful for AMV)</p>
<h3>Search Algorithm Changed</h3>
<h6>21 Dec 2015</h6>
<p>Switched to use a new searching algorithm. The search is slower but more accurate.</p>
<h3>Public Beta</h3>
<h6>19 Dec 2015</h6>
<p>Adding some informative pages.</p>
<p>&nbsp;</p>
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
