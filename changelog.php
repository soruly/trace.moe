<?php
header("Link: </css/style.css>; rel=preload; as=style", false);
header("Link: </css/bootstrap.min.css>; rel=preload; as=style", false);
header("Link: </js/analytics.js>; rel=preload; as=script", false);
header("Link: </js/nav_v1.js>; rel=preload; as=script", false);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Search Anime by ScreenShot. Lookup the exact moment and the episode.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#60738b" />
    <title>WAIT: What Anime Is This? - Changelog</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="icon" type="image/png" href="/favicon128.png" sizes="128x128">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script src="/js/analytics.js" defer></script>
</head>

<body>
    <nav class="wait-navbar">
        <div class="container">
            <a class="wait-navbar__hamburger" href="javascript:void(0);" onclick="waitNav()">
                <span class="glyphicon glyphicon-menu-hamburger"></span>
            </a>
            <div id="wait-nav" class="wait-navbar__links">
                <a href="/">Home</a>
                <a href="/about">About</a>
                <a href="/changelog" class="active">Changelog</a>
                <a href="/faq">FAQ</a>
                <a href="/terms">Terms</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <ul class="wait-info__group">
            <li>
                <h2>Changelog</h2>
            </li>
            <?php  /* Changelog
                <li>
                    <h3><span class="date new">DD MM YYYY</span>Title</h3>
                    <div>
                        <p>Content</p>
                        <p><img/></p>
                        <ol>...</ol>
                    </div>
                </li>

                if you have any questions or clarifications you can message me anytime
            */ ?>
            <li>
                <h3><span class="date">13 Nov 2017</span>Added auto black border crop</h3>
                <div>
                    <p>This will automatically detect and crop black borders on search image, significantly increase the accuracy on bad screenshots.</p>
                    <p>This is applied to both web, telegram bot and all API clients.</p>
                    <p>This is achieved by openCV using a simple python script.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">26 Aug 2017</span>Telegram Channel</h3>
                <div>
                    <p>You can subscribe the <a href="https://telegram.me/whatanimeupdates">Telegram Channel</a> for database and news updates.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">24 Aug 2017</span>GIF and video support for Telegram Bot</h3>
                <div>
                    <p>You can now send GIF or video to the <a href="https://telegram.me/WhatAnimeBot">Telegram Bot</a>.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">23 Aug 2017</span>Group chat support for Telegram Bot</h3>
                <div>
                    <p>You can now add the <a href="https://telegram.me/WhatAnimeBot">Telegram Bot</a> to Telegram group. Use @ to mention the bot on any photo to search.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">5 Aug 2017</span>Fix search requests failed when Google Analytics is blocked</h3>
                <div>
                    <p>Identified and fixed an issue where browsers failed to search due to blocked scripts.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">26 Jul 2017</span>Added 2017-04 database dump</h3>
                <div>
                    <p>Database dump updated to 2017-04.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">14 Jun 2017</span>Added demo for the upcoming search engine update</h3>
                <div>
                    <p>Take a look at a demo on <a href="https://demo.whatanime.ga">https://demo.whatanime.ga</a></p>
                </div>
            </li>
            <li>
                <h3><span class="date">5 Jun 2017</span>New Icon</h3>
                <div>
                    <p><img src="/favicon128.png" alt="favicon"></p>
                    <p>This is the new icon for whatanime.ga</p>
                </div>
            </li>
            <li>
                <h3><span class="date">4 Jun 2017</span>2017 Presentation slides updates</h3>
                <div>
                    <p>Want to know more about whatanime.ga? Read the <a href="https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga-2017.slide">Presentation slides on June 2017</a>.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">30 Apr 2017</span>Search changes</h3>
                <div>
                    <p>You can now search scenes with any aspect ratio. Thumbnail preview also respect aspect ratio now. Recaptcha is removed, you must wait up to 10 minutes once you have reached search quota limit (20 search per 10 minutes). Homepage code has been re-written, the webpage now loads faster. And a new loading animation was added.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">23 Apr 2017</span>Search for more results</h3>
                <div>
                    <p>You can now keep searching the database for more results. Previously, the search would stop when it has found any result > 90% similarity. Now keep searching to discover more results with even higher similarity!</p>
                </div>
            </li>
            <li>
                <h3><span class="date">18 Apr 2017</span>Search in specific season</h3>
                <div>
                    <p>You can now select a particular year / season to search. If you like this project, feel free to <a href="https://www.patreon.com/soruly">Support trace.moe <s>whatanime.ga</s> on Patreon.</a></p>
                </div>
            </li>
            <li>
                <h3><span class="date">15 Apr 2017</span>System maintenance</h3>
                <div>
                    <p>Server upgrade and cleanup was completed on 15 Apr 2017. An additional hard drive and new network adapter has been installed.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">14 Apr 2017</span>System status page</h3>
                <div>
                    <p>You can now see the system status in <a href="https://status.trace.moe">https://status.trace.moe</a> <s><a href="https://status.whatanime.ga">https://status.whatanime.ga</a></s> (Powered by UptimeRobot).</p>
                </div>
            </li>
            <li>
                <h3><span class="date">23 Feb 2017</span>Partial service interruption</h3>
                <div>
                    <p>Anime info panel was not showing since Feb 21 21:13 UTC, the service has been restored on Feb 23 03:34 UTC.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">21 Feb 2017</span>Image proxy relocated</h3>
                <div>
                    <p>The image proxy server has been moved from Singapore (Digital Ocean) to Tokyo (Linode). It may affect loading times of images from ?url= params.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">4 Apr 2017</span>Video preview for Telegram Bot</h3>
                <div>
                    <p>The <a href="https://telegram.me/WhatAnimeBot">Telegram Bot</a> will now sends you a video preview.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">17 Jan 2017</span>Official API released</h3>
                <div>
                    <p>The official API is now open for testing. Interested developers may read the page on <a href="https://soruly.github.io/whatanime.ga/">GitHub Pages</a>.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">16 Nov 2016</span>WebExtension updated</h3>
                <div>
                    <p>The image extraction method of WebExtension has changed. This would be able to fix some issues on grabbing the correct image on webpage to search. Now the Extension also supports Microsoft Edge. You may try loading the zip from <a href="https://github.com/soruly/whatanime.ga-WebExtension/releases">GitHub</a> by enabling extension developer features in about:flags.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">11 Nov 2016</span>Enabled autoplay on mobile</h3>
                <div>
                    <p>Now mobile devies will mute and autoplay the video preview.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">29 Oct 2016</span>Added URL params for playback options</h3>
                <div>
                    <p>You can now use URL params to control playback options, for example:</p>
                    <pre>https://trace.moe/?autoplay=0&loop&mute=1&url=</pre>
                </div>
            </li>
            <li>
                <h3><span class="date">23 Oct 2016</span>Improved performance</h3>
                <div>
                    <p>Reduced search result candidates from 10 Million to 3 Million. This would reduce accuracy but greatly improves performance.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">5 Oct 2016</span>Added links to database dump</h3>
                <div>
                    <p>You can now download a complete dump of the database.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">1 Oct 2016</span>Added more server status</h3>
                <div>
                    <p>You can see server load and recently indexed files in /about</p>
                </div>
            </li>
            <li>
                <h3><span class="date">1 Oct 2016</span>Adding Raw Anime</h3>
                <div>
                    <p>The database has started indexing raw anime from now on.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">14 Sep 2016</span>Added Telegram Bot</h3>
                <div>
                    <p>This telegram bot can tell you where an anime screenshot is taken from. Just send / forward an image to <a href="https://telegram.me/WhatAnimeBot">https://telegram.me/WhatAnimeBot</a>.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">14 Aug 2016</span>WebExtension Changes</h3>
                <div>
                    <p>The ?auto url param is no longer used. Now it would always automatically search.</p>
                    <p>WebExtension has updated. Now it would copy and paste using dataURL in background. It allows searching images from pages that's not publicly available such as Facebook Feeds. It also supports searching from HTML5 videos using the frame extracted at the moment you click it. Now extensions no longer use the ?url to send search images.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">14 Aug 2016</span>WebExtension Changes</h3>
                <div>
                    <p>The ?auto url param is no longer used. Now it would always automatically search.</p>
                    <p>WebExtension has updated. Now it would copy and paste using dataURL in background. It allows searching images from pages that's not publicly available such as Facebook Feeds. It also supports searching from HTML5 videos using the frame extracted at the moment you click it. Now extensions no longer use the ?url to send search images.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">1 Jul 2016</span>WebExtension for Chrome, Firefox and Opera</h3>
                <div>
                    <p>
                        <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp">Chrome Extension</a>,
                        <a href="https://addons.mozilla.org/en-US/firefox/addon/search-anime-by-screenshot/">Firefox Add-on</a>,
                        <a href="https://addons.opera.com/en/extensions/details/search-anime-by-screenshot/">Opera Add-on</a> has been relased.
                    </p>
                    <p>Source code available on <a href="https://github.com/soruly/whatanime.ga-WebExtension">GitHub</a></p>
                </div>
            </li>
            <li>
                <h3><span class="date">30 Jun 2016</span>Improved anime title language</h3>
                <div>
                    <p>It now shows anime titles according to users' browser language.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">30 May 2016</span>Added loading icon</h3>
                <div>
                    <p>The thumbnail may not be at the exact moment, since the seeking is not very accurate. Play the preview to see if it's what you are looking for.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">6 May 2016</span>Fixed some bugs in URL loading</h3>
                <div>
                    <p>In case the image cannot be loaded, upload the image from file or copy image itself (not URL) then Ctrl+V</p>
                </div>
            </li>
            <li>
                <h3><span class="date">23 Apr 2016</span>Speed up image load from URL</h3>
                <div>
                    <p>Images load from URL would be compressed. This would speed up loading GIF and large images from URL.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">18 Apr 2016</span>Performance Tweaks</h3>
                <div>
                    <p>Server will now cache some search results. Search results would be cached for 5-30 minutes. The better the search results, the longer the results would be cached.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">8 Apr 2016</span>Updated Chrome Extension</h3>
                <div>
                    <p>Once the image completes loading, it would search automatically. You can change the setting in Chrome Extension. Also see how you can search in Firefox in <a href="/faq">FAQ</a>.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">7 Apr 2016</span>Added index cache status</h3>
                <div>
                    <p>You can now see how much data is cached in RAM from About page. The higher the percentage faster the search. It usually stays around 33% due to limited RAM.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">7 Apr 2016</span>Incorrect timestamp issue identified</h3>
                <div>
                    <p>There has been some incorrect timestamp in search results due to image analyze scripts parsing outputs of ffmpeg incorrectly. The script has been updated now. From now on new animes should have a correct timestamp, while it would take at about two months to fix already indexed animes. The new script is also 33% faster when indexing anime.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">10 Mar 2016</span>Fixes some image editing issue</h3>
                <div>
                    <p>Image would sometimes gone black when clicking fit / flip button. Now fixed.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">6 Mar 2016</span>Added Fit Width / Height option</h3>
                <div>
                    <p>You can now choose to Fit Width / Height for your search image. Also fixed some flickering issue on previews.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">3 Mar 2016</span>Fixed some Image URL issue</h3>
                <div>
                    <p>Fixed some cross-site image URL linking issue. Most image URL should load now.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">1 Mar 2016</span>New Anime Info Panel UI</h3>
                <div>
                    <p>A better layout for more Anime information.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">1 Mar 2016</span>Added Image URL Option</h3>
                <div>
                    <p>You can now search by Image URL</p>
                </div>
            </li>
            <li>
                <h3><span class="date">28 Feb 2016</span>Added Safe Search Option</h3>
                <div>
                    <p>The Safe Search Option can hide most Hentai Anime from search result. But you should aware that some regular season Animes can still be obscene. (NSFW)</p>
                </div>
            </li>
            <li>
                <h3><span class="date">28 Feb 2016</span>Try the Chrome Extension</h3>
                <div>
                    <p>Now you can use the <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp">Chrome Extension</a> to search.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">28 Feb 2016</span>Added some Heitai Anime</h3>
                <div>
                    <p>About 168 Heitai Anime series has been added.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">28 Feb 2016</span>Added sample screenshot in FAQ</h3>
                <div>
                    <p>To help users to understand how the search engine works, we have added some good and bad screenshots in <a href="/faq">FAQ</a>.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">21 Feb 2016</span>Performance Improvement</h3>
                <div>
                    <p>Improved caching method to warmup cold data.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">20 Feb 2016</span>More fixes</h3>
                <div>
                    <p>Database has been cleaned up and reloaded. This should fix most video previews. Fixed some anime titles still being null.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">17 Feb 2016</span>Bug Fix</h3>
                <div>
                    <p>Fixed the empty search result. The issue has been resolved. A large number of files has been relocated, video preview may be missing for some search results.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">1 Jan 2016</span>Improved Performance</h3>
                <div>
                    <p>Increased cache size to improve performance when the server has been idle for a long time.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">31 Dec 2015</span>Added a Flip Button</h3>
                <div>
                    <p>You may now flip the image before searching. If you can't find a match, try to flip your image and search again. (Especially useful for AMV)</p>
                </div>
            </li>
            <li>
                <h3><span class="date">21 Dec 2015</span>Search Algorithm Changed</h3>
                <div>
                    <p>Switched to use a new searching algorithm. The search is slower but more accurate.</p>
                </div>
            </li>
            <li>
                <h3><span class="date">19 Dec 2015</span>Public Beta</h3>
                <div>
                    <p>Adding some informative pages.</p>
                </div>
            </li>
        </ul>
    </div>

    <footer class="footer">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/changelog">Changelog</a></li>
                <li><a href="/faq">FAQ</a></li>
                <li><a href="/terms">Terms</a></li>
            </ol>
        </div>
    </footer>

    <script src="/js/nav_v1.js"></script>
</body>

</html> 