<?php
header("Link: </css/style.css>; rel=preload; as=style", false);
header("Link: </css/bootstrap.min.css>; rel=preload; as=style", false);
header("Link: </js/analytics.js>; rel=preload; as=script", false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="description" content="Search Anime by ScreenShot. Lookup the exact moment and the episode.">
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

<h3>Website updated to use new trace.moe API</h3>
<h6>9 Feb 2021</h6>
<p>
- the API is now served by the 96 core servers on cloud<br>
- front end code rewrite with ES6+ syntax. Dropped IE and some old browsers support. <br>
- safe search button is removed, it shows only when NSFW results are found<br>
- search will now perform first 3 trials at once (in DB), you will no longer see results appearing one after another<br>
- the candidate size is now set automatically on server side. If it finds that some search results are trimmed, it would increase candidate size and search again. This is done entirely on server side.<br>
- keep searching button removed. I'm pretty sure you can no longer find relevant results with that function.<br>
- Flip button removed. (caused many bugs in website, before I add this back, please flip the image on your own.)<br>
- JCD algo button removed. (will add this back after migration complete, you can still use this algo via trace.moe API)<br>
- loop/autoplay/mute button removed. (I may add back an unmute button if possible)<br>
</p>

<h3>Updates to trace.moe API</h3>
<h6>30 Jan 2021</h6>
<p>
- Change: image preview endpoint has changed, the url is now consistent with video preview<br>
- New: size param for image/video preview<br>
<br>
Please refer to <a href="https://soruly.github.io/trace.moe/#/#previews">https://soruly.github.io/trace.moe/#/#previews</a><br>
</p>

<h3>trace.moe database dump 2020-10</h3>
<h6>18 Oct 2020</h6>
<p>
<br>
This is the most recent database dump for trace.moe which contains image hashes of ~5000 anime titles. (No video files included)<br>
<br>
Comparing to last DB dump, this data set has recent anime added, and has replaced many subbed versions with raw anime.<br>
<br>
These can be loaded into sola database for searching locally. You can follow the project on GitHub if you are interested.<br>
<br>
<a href="https://github.com/soruly/sola">https://github.com/soruly/sola</a><br>
</p>

<h3>New Image Search Algorithm (JCD)</h3>
<h6>13 Apr 2020</h6>
<p>
A new search algorithm has been added to trace.moe. You can try this using the "Use new algo" check box near the search button. This new algorithm has better support on flipped and cropped images. See results above.<br>
<br>
This new algorithm still needs fine-tuning of its parameters, so I'm now opening this option for everyone to try. For developers using the API, you can use /search?method=jc . In upcoming months, I'll review the performance and accuracy of these two algorithms and see if JCD is good enough to replace ColorLayout. Or else, methods to combine the two.<br>
<br>
The new algorithm, JCD (Joint Composite Descriptor) is composed of CEDD (Color and Edge Directivity Descriptor) and FCTH (Fuzzy Color and Texture Histogram), takes both color, edge (shape) and texture into image analysis. trace.moe has been using ColorLayout descriptor, which does not analyze the image edge and shape but only the distribution of colors in a 8x8 grid. With this new algorithm, it makes it possible to search for flipped/cropped images which previously fails.<br>
<br>
Note that none of these Image Descriptors are invented by me. If you're interested in the principles of the the algorithm(s), please read the paper from the original author. and its open source implementation, LIRE.<br>
<br>
When indexed on the same video data set, the size of indexed database (extracted) is 232GB, 85% larger than that of ColorLayout (125GB). The database cache of two algos and memory for apache solr now occupies 420GB RAM (out of 512GB) RAM on server.<br>
<br>
Serving the database for the new algorithm requires powerful servers. Please continue to support this project for ACG fans! :3<br>
</p>

<h3>trace.moe database dump 2020-04</h3>
<h6>13 Apr 2020</h6>
<p>
This is the most recent database dump for trace.moe which contains image hashes of ~100,000 anime files. (No video files included)<br>
<br>
Comparing to last DB dump, this data set has recent anime added and versions from different subgroups de-duplicated. For most anime, only one version of the same episode is kept in db. This results a smaller database size and faster search time.<br>
<br>
These can be loaded into sola database for searching locally. I'm still modifying sola and document the the steps how to do so. You can follow the project on GitHub if you are interested.<br>
<br>
<a href="https://github.com/soruly/sola">https://github.com/soruly/sola</a><br>
</p>

<h3>Added dark theme support</h3>
<h6>14 Sep 2019</h6>
<p>
If your OS is in dark mode, it would switch to dark mode automatically without any settings.<br>
For windows, it'd be in settings => personalization => color<br>
For macOS, it'd be in system preferences => general => appearance <br>
</p>

<h3>Added image URL support to trace.moe API</h3>
<h6>4 May 2019</h6>
<p>
You can now easily use http://trace.moe API directly with image URL<br>
<br>
<a href="https://soruly.github.io/trace.moe/">https://soruly.github.io/trace.moe/</a>
</p>

<h3>trace.moe database dump 2019-04</h3>
<h6>4 May 2019</h6>
<p>
This is the most recent database dump for trace.moe which contains image hashes of ~100,000 anime files. (No video files included)<br>
<br>
These can be loaded into sola database for searching locally. I'm still modifying sola and document the the steps how to do so. You can follow the project on GitHub if you are interested.<br>
<a href="https://github.com/soruly/sola">https://github.com/soruly/sola</a><br>
</p>

<h3>Optimizing website loading speed</h3>
<h6>1 May 2019</h6>
<p>
Recently I've been improving webpage loading times in various ways:<br>
- Re-write webpage js to remove heavy libraries like jQuery / bootstrap.js<br>
- Use HTTP/2 Push to reduce round-trip times<br>
- Upgraded Cloudflare CDN to pro plan ($20/month)<br>
<br>
Now the webpage can complete loading in just 90ms!! (from regions close to origin server)<br>
<br>
trace.moe is now scoring 98 and 100 in Google PageSpeed Insights<br>
<br>
<a href="https://developers.google.com/speed/pagespeed/insights/?url=https%3A%2F%2Ftrace.moe&tab=desktop">https://developers.google.com/speed/pagespeed/insights/?url=https%3A%2F%2Ftrace.moe&tab=desktop</a><br>
<br>
I've also improved website security to score A+ (115/100) in Mozilla Observatory test.<br>
<br>
<a href="https://observatory.mozilla.org/analyze/trace.moe">https://observatory.mozilla.org/analyze/trace.moe</a><br>
<br>
Recently I've found that Cloudflare's free plan did not route to nearest edge server due to it's network capacity. When traffic is busy for the region during that period, it may route to thousands of miles away which increase page load time by hundreds of milliseconds. That's why I've upgraded to Cloudflare pro plan to ensure the website is consistently fast from the whole world. I hope your support can cover the increased cost.<br>
</p>

<h3>Recent updates to trace.moe</h3>
<h6>3 Feb 2019</h6>
<p>
New web UI layout for mobile devices!<br>
A few days ago, I redesigned a the webpage layout a bit to better support mobile devices. (finally!) Though the layout isn't very perfect, at least it's easier to use and browse compared to the fixed viewport before. Try the webpage now at https://trace.moe<br>
<br>
Natural Scene Cutter for video preview<br>
I started a new project late October 2018 to cut video scene previews naturally, by detecting timestamp boundaries of a scene. This method is better than the fixed time offset cutting. So you can loop the video of the scene. I'm still writing description of this project, which you can find it on Github <a href="https://github.com/soruly/trace.moe-media">https://github.com/soruly/trace.moe-media</a><br>
<br>
GIF Preview for Telegram bot<br>
To try the natural scene cutter, you can use the Telegram bot.<br>
<a href="https://telegram.me/WhatAnimeBot">https://telegram.me/WhatAnimeBot</a><br>
Send an image with caption "mute", and it will return a muted video preview (same as GIF), which can loop a scene.<br>
<br>
API updates<br>
The natural scene cutter can also be used via API now.<br>
The API and API docs have updated to resolve some confusions around search image format and error handling issues. Now the API has removed strict restrictions on image format and is supporting both JSON and FORM POST. Details are written in <a href="https://soruly.github.io/trace.moe/">https://soruly.github.io/trace.moe/</a><br>
<br>
Dockerizing sola<br>
sola is now mostly dockerized. It is a lot easier for developers to setup their own video scene search engine now. Instructions are written in details in the project:<br>
<a href="https://github.com/soruly/sola">https://github.com/soruly/sola</a><br>
<br>
<br>
The domain incident hit this project quite hard last October. But with the help of fans and developers around the world, the daily search queries has now restored to same levels as half a year ago. Thank you for all your support!! ;)<br>
<br>
</p>

<h3>Moving to new domain: trace.moe</h3>
<h6>22 Oct 2018</h6>
<p>
Yesterday, the .ga domain provided by Freenom (domain name registrar) was suddenly suspended. I had no choice but immediately moved to trace.moe which I've been planning to move to.<br>
As of today, officially supported integrations (WebExtensions, Telegram bot), and some major integrations (Discord, SauceNAO) has been updated to the new domain. In upcoming days I'll work with developers of remaining 3rd party integrations (including API users) to notify the change.<br>
<br>
Why the name trace.moe?<br>
This search engine tells you more than the anime name, but actually trace back the moment of the scene. And quite a number of users actually need "time tracing" even they already know the anime. That's why I think using "trace" instead of "whatanime" better describes this search engine.<br>
</p>

<h3>open sourcing scene search with sola</h3>
<h6>2 May 2018</h6>
<p>
The last piece of puzzle in whatanime.ga has published.<br>
The whatanime repository on GitHub has gain lot of developers' attention. However, it only include the code for the website and is not a fully functional system. Some of them are puzzled when they try to look into the code.<br>
<br>
Last month, I've re-written those messy scripts into a new project - sola.<br>
<br>
This include all the scripts that whatanime.ga currently used to put mp4 video files into solr for search. It has been running for a month, so this should be stable enough to publish. sola is not limited to searching anime, any types of video (like movies, TV shows) can also be indexed. It does not depends on whatanime and anilist, the only dependeny is liresolr. So users that doesn't need anime info and web UI can avoid those complexities.<br>
<br>
sola is a node.js app. Many developers would feel easy to read and modify the code. Hopefully this would encourage contribution to the project. It has just ~700 lines of code in total. For developers that doesn't like Javascript (or would like to avoid GPLv3), it's not difficult to rewrite the whole thing.<br>
<br>
I've written a setup guide for developers to easily setup their own video scene search engine. Spread the news to developers and let them try!<br>
<br>
<a href="https://github.com/soruly/sola">https://github.com/soruly/sola</a><br>
</p>

<h3>Speeding up search on whatanime.ga !</h3>
<h6>2 Apr 2018</h6>
<p>
<iframe width="640" height="640" src="https://www.youtube.com/embed/HjL5O3k3C7s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
<br>
New testing site on https://beta.whatanime.ga<br>
I've been testing a new beta site with improved performance on search speed.<br>
A copy of the 130GB solr core from http://whatanime.ga is split into 10 smaller cores (10-16GB each, with 59-89 million hashes). Each search queries all 10 solr cores in parallel and then merge back. Early tests shows that it can query 950 million image hashes in 1.35 seconds! Almost 10 times as much than the existing one.<br>
<br>
I've rewritten a lot of backend scripts into a nice CLI tool. I'm going to publish it on github later this month, after I've completed the docs and tutorial. For experienced developers, it will be a lot easier to setup their own video reverse search engine.<br>
</p>

<h3>Added auto black border crop</h3>
<h6>13 Nov 2017</h6>
<p>
This will automatically detect and crop black borders on search image, significantly increase the accuracy on bad screenshots<br>
This is applied to both web, telegram bot and all API clients<br>
This is achieved by openCV using a simple python script
</p>

<h3>Telegram Channel</h3>
<h6>26 Aug 2017</h6>
<p>You can subscribe the <a href="https://telegram.me/whatanimeupdates">Telegram Channel</a> for database and news updates.</p>

<h3>GIF and video support for Telegram Bot</h3>
<h6>24 Aug 2017</h6>
<p>You can now send GIF or video to the <a href="https://telegram.me/WhatAnimeBot">Telegram Bot</a>.</p>

<h3>Group chat support for Telegram Bot</h3>
<h6>23 Aug 2017</h6>
<p>You can now add the <a href="https://telegram.me/WhatAnimeBot">Telegram Bot</a> to Telegram group. Use @ to mention the bot on any photo to search.</p>

<h3>Fix search requests failed when Google Analytics is blocked</h3>
<h6>5 Aug 2017</h6>
<p>Identified and fixed an issue where browsers failed to search due to blocked scripts.</p>

<h3>Added 2017-04 database dump</h3>
<h6>26 Jul 2017</h6>
<p>Database dump updated to 2017-04.</p>

<h3>Added demo for the upcoming search engine update</h3>
<h6>14 Jun 2017</h6>
<p>Take a look at a demo on <a href="https://demo.whatanime.ga">https://demo.whatanime.ga</a></p>

<h3>New Icon</h3>
<h6>5 Jun 2017</h6>
<p><img src="/favicon128.png" alt="favicon"><br>This is the new icon for whatanime.ga</p>

<h3>2017 Presentation slides updates</h3>
<h6>4 Jun 2017</h6>
<p>Want to know more about whatanime.ga? Read the <a href="https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga-2017.slide">Presentation slides on June 2017</a>.</p>

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
<p>Now mobile devices will mute and autoplay the video preview.</p>

<h3>Added URL params for playback options</h3>
<h6>29 Oct 2016</h6>
<p>You can now use URL params to control playback options, for example:<pre>https://trace.moe/?autoplay=0&loop&mute=1&url=</pre></p>

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
