<?php
header("Link: </css/app_fallback.css>; rel=preload; as=style", false);
header("Link: </css/app.css>; rel=preload; as=style", false);
header("Link: </css/bootstrap.min.css>; rel=preload; as=style", false);
header("Link: </js/analytics.js>; rel=preload; as=script", false);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- (๑・ω・๑) -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Search Anime by ScreenShot. Lookup the exact moment and the episode.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3f51b5" />
    <title>WAIT: What Anime Is This? - Changelog</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="icon" type="image/png" href="/favicon128.png" sizes="128x128">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app_fallback.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <script src="/js/analytics.js" defer></script>
</head>

<body class="changelog">
    <nav class="navbar">
        <div class="width">
            <a href="javascript:void(0);" class="navbar__hamburger">
                <span class="glyphicon glyphicon-menu-hamburger"></span>
            </a>
            <div class="navbar__menu">
                <a class="navbar__item" href="/">Home</a>
                <a class="navbar__item" href="/about">About</a>
                <a class="navbar__item navbar__item--active" href="/changelog">Changelog</a>
                <a class="navbar__item" href="/faq">FAQ</a>
                <a class="navbar__item" href="/terms">Terms</a>
            </div>
        </div><!-- /.width -->
    </nav><!-- /.navbar -->

    <div class="w-alert">
        <div class="width">
            <p class="w-alert__text">Read recent updates to trace.moe | soruly on <a href="https://www.patreon.com/posts/24430008" class="w-alert__link">Patreon!</a></p>
        </div><!-- /.width -->
    </div><!-- /.w-alert -->

    <div class="width">
        <ul class="info">
            <li class="info__list">
                <h2 class="info__title">Changelog</h2>
            </li>
            <!-- Note Layout
            <li class="info__list">
                <span class="info__date"></span>
                <h3 class="info__heading"></h3>
                <h4 class="info__subheading"></h4>
                <p class="info__content">
                    <a href=""></a>
                    <img src="" alt="">
                </p>
            </li>
            -->
            <li class="info__list">
                <span class="info__date">3 Feb 2019</span>
                <h3 class="info__heading">Recent updates to trace.moe</h3>
                <p class="info__content">
                    The domain incident hit this project quite hard last October.
                    But with the help of fans and developers around the world, the daily search queries has now restored to same levels as half a year ago.
                    Thank you for all your support!! ;)
                </p>
                <h4 class="info__subheading">New web UI layout for mobile devices!</h4>
                <p class="info__content">
                    A few days ago, I redesigned a the webpage layout a bit to better support mobile devices. (finally!)
                    Though the layout isn't very perfect, at least it's easier to use and browse compared to the fixed viewport before.
                    Try the webpage now at <a href="https://trace.moe">https://trace.moe</a>
                </p>
                <h4 class="info__subheading">Natural Scene Cutter for video preview</h4>
                <p class="info__content">
                    I started a new project late October 2018 to cut video scene previews naturally, by detecting timestamp boundaries of a scene.
                    This method is better than the fixed time offset cutting.
                    So you can loop the video of the scene.
                    I'm still writing description of this project, which you can find it on Github <a href="https://github.com/soruly/trace.moe-media">https://github.com/soruly/trace.moe-media</a>
                </p>
                <h4 class="info__subheading">GIF Preview for Telegram bot</h4>
                <p class="info__content">
                    To try the natural scene cutter, you can use the Telegram bot. <a href="https://telegram.me/WhatAnimeBot">https://telegram.me/WhatAnimeBot</a>
                    Send an image with caption "mute", and it will return a muted video preview (same as GIF), which can loop a scene.
                </p>
                <h4 class="info__subheading">API updates</h4>
                <p class="info__content">The natural scene cutter can also be used via API now.</p>
                <p class="info__content">
                    The API and API docs have updated to resolve some confusions around search image format and error handling issues.
                    Now the API has removed strict restrictions on image format and is supporting both JSON and FORM POST.
                    Details are written in <a href="https://soruly.github.io/trace.moe/">https://soruly.github.io/trace.moe/</a>
                </p>
                <h4 class="info__subheading">Dockerizing sola</h4>
                <p class="info__content">
                    sola is now mostly dockerized.
                    It is a lot easier for developers to setup their own video scene search engine now.
                    Instructions are written in details in the project: <a href="https://github.com/soruly/sola">https://github.com/soruly/sola</a>
                </p>
            </li>
            <li class="info__list">
                <span class="info__date">22 Oct 2018</span>
                <h3 class="info__heading">Moving to new domain: trace.moe</h3>
                <p class="info__content">
                    Yesterday, the .ga domain provided by Freenom (domain name registrar) was suddenly suspended.
                    I had no choice but immediately moved to <a href="https://trace.moe">trace.moe</a> which I've been planning to move to.
                </p>
                <p class="info__content">
                    As of today, officially supported integrations (WebExtensions, Telegram bot), and some major integrations (Discord, SauceNAO) has been updated to the new domain.
                    In upcoming days I'll work with developers of remaining 3rd party integrations (including API users) to notify the change.
                </p>
                <p class="info__content">
                    <strong>Why the name <a href="https://trace.moe">trace.moe</a>?</strong> This search engine tells you more than the anime name, but actually trace back the moment of the scene.
                    And quite a number of users actually need "time tracing" even they already know the anime.
                    That's why I think using "trace" instead of "whatanime" better describes this search engine.
                </p>
            </li>
            <li class="info__list">
                <span class="info__date">2 Apr 2018</span>
                <h3 class="info__heading">Speeding up search on whatanime.ga!</h3>
                <p class="info__content">New testing site on <a href="https://beta.whatanime.ga">https://beta.whatanime.ga</a></p>
                <p class="info__content">
                    I've been testing a new beta site with improved performance on search speed.
                    A copy of the 130GB solr core from <a href="http://whatanime.ga">http://whatanime.ga</a> is split into 10 smaller cores (10-16GB each, with 59-89 million hashes).
                    Each search queries all 10 solr cores in parallel and then merge back. Early tests shows that it can query 950 million image hashes in 1.35 seconds!
                    Almost 10 times as much than the existing one.
                </p>
                <p class="info__content">
                    I've rewritten a lot of backend scripts into a nice CLI tool.
                    I'm going to publish it on github later this month, after I've completed the docs and tutorial.
                    For experienced developers, it will be a lot easier to setup their own video reverse search engine.
                </p>
            </li>
            <li class="info__list">
                <span class="info__date">13 Nov 2017</span>
                <h3 class="info__heading">Added auto black border crop</h3>
                <p class="info__content">This will automatically detect and crop black borders on search image, significantly increase the accuracy on bad screenshots.</p>
                <p class="info__content">This is applied to both web, telegram bot and all API clients.</p>
                <p class="info__content">This is achieved by openCV using a simple python script.</p>
            </li>
            <li class="info__list">
                <span class="info__date">26 Aug 2017</span>
                <h3 class="info__heading">Telegram Channel</h3>
                <p class="info__content">You can subscribe the <a href="https://telegram.me/whatanimeupdates">Telegram Channel</a> for database and news updates.</p>
            </li>
            <li class="info__list">
                <span class="info__date">24 Aug 2017</span>
                <h3 class="info__heading">GIF and video support for Telegram Bot</h3>
                <p class="info__content">You can now send GIF or video to the <a href="https://telegram.me/WhatAnimeBot">Telegram Bot</a>.</p>
            </li>
            <li class="info__list">
                <span class="info__date">23 Aug 2017</span>
                <h3 class="info__heading">Group chat support for Telegram Bot</h3>
                <p class="info__content">You can now add the <a href="https://telegram.me/WhatAnimeBot">Telegram Bot</a> to Telegram group. Use @ to mention the bot on any photo to search.</p>
            </li>
            <li class="info__list">
                <span class="info__date">5 Aug 2017</span>
                <h3 class="info__heading">Fix search requests failed when Google Analytics is blocked</h3>
                <p class="info__content">Identified and fixed an issue where browsers failed to search due to blocked scripts.</p>
            </li>
            <li class="info__list">
                <span class="info__date">26 Jul 2017</span>
                <h3 class="info__heading">Added 2017-04 database dump</h3>
                <p class="info__content">Database dump updated to 2017-04.</p>
            </li>
            <li class="info__list">
                <span class="info__date">14 Jun 2017</span>
                <h3 class="info__heading">Added demo for the upcoming search engine update</h3>
                <p class="info__content">Take a look at a demo on <a href="https://demo.whatanime.ga">https://demo.whatanime.ga</a></p>
            </li>
            <li class="info__list">
                <span class="info__date">5 Jun 2017</span>
                <h3 class="info__heading">New Icon</h3>
                <p class="info__content"><img src="/favicon128.png" alt="favicon"></p>
                <p class="info__content">This is the new icon for whatanime.ga</p>
            </li>
            <li class="info__list">
                <span class="info__date">4 Jun 2017</span>
                <h3 class="info__heading">2017 Presentation slides updates</h3>
                <p class="info__content">
                    Want to know more about whatanime.ga? Read the
                    <a href="https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga-2017.slide">Presentation slides on June 2017</a>.
                </p>
            </li>
            <li class="info__list">
                <span class="info__date">30 Apr 2017</span>
                <h3 class="info__heading">Search changes</h3>
                <p class="info__content">
                    You can now search scenes with any aspect ratio.
                    Thumbnail preview also respect aspect ratio now.
                    Recaptcha is removed, you must wait up to 10 minutes once you have reached search quota limit (20 search per 10 minutes).
                    Homepage code has been re-written, the webpage now loads faster.
                    And a new loading animation was added.
                </p>
            </li>
            <li class="info__list">
                <span class="info__date">23 Apr 2017</span>
                <h3 class="info__heading">Search for more results</h3>
                <p class="info__content">
                    You can now keep searching the database for more results.
                    Previously, the search would stop when it has found any result > 90% similarity.
                    Now keep searching to discover more results with even higher similarity!
                </p>
            </li>
            <li class="info__list">
                <span class="info__date">18 Apr 2017</span>
                <h3 class="info__heading">Search in specific season</h3>
                <p class="info__content">
                    You can now select a particular year / season to search.
                    If you like this project, feel free to <a href="https://www.patreon.com/soruly">Support whatanime.ga on Patreon</a>.
                </p>
            </li>
            <li class="info__list">
                <span class="info__date">15 Apr 2017</span>
                <h3 class="info__heading">System maintenance</h3>
                <p class="info__content">
                    Server upgrade and cleanup was completed on 15 Apr 2017.
                    An additional hard drive and new network adapter has been installed.
                </p>
            </li>
            <li class="info__list">
                <span class="info__date">14 Apr 2017</span>
                <h3 class="info__heading">System status page</h3>
                <p class="info__content">You can now see the system status in <a href="https://status.whatanime.ga">https://status.whatanime.ga</a> (Powered by UptimeRobot).</p>
            </li>
            <li class="info__list">
                <span class="info__date">23 Feb 2017</span>
                <h3 class="info__heading">Partial service interruption</h3>
                <p class="info__content">Anime info panel was not showing since Feb 21 21:13 UTC, the service has been restored on Feb 23 03:34 UTC.</p>
            </li>
            <li class="info__list">
                <span class="info__date">21 Feb 2017</span>
                <h3 class="info__heading">Image proxy relocated</h3>
                <p class="info__content">The image proxy server has been moved from Singapore (Digital Ocean) to Tokyo (Linode). It may affect loading times of images from ?url= params.</p>
            </li>
            <li class="info__list">
                <span class="info__date">4 Apr 2017</span>
                <h3 class="info__heading">Video preview for Telegram Bot</h3>
                <p class="info__content">The <a href="https://telegram.me/WhatAnimeBot">Telegram Bot</a> will now sends you a video preview.</p>
            </li>
            <li class="info__list">
                <span class="info__date">17 Jan 2017</span>
                <h3 class="info__heading">Official API released</h3>
                <p class="info__content">The official API is now open for testing. Interested developers may read the page on <a href="https://soruly.github.io/whatanime.ga/">GitHub Pages</a>.</p>
            </li>
            <li class="info__list">
                <span class="info__date">16 Nov 2016</span>
                <h3 class="info__heading">WebExtension updated</h3>
                <p class="info__content">
                    The image extraction method of WebExtension has changed. 
                    This would be able to fix some issues on grabbing the correct image on webpage to search. 
                    Now the Extension also supports Microsoft Edge. 
                    You may try loading the zip from <a href="https://github.com/soruly/whatanime.ga-WebExtension/releases">GitHub</a> by enabling extension developer features in about:flags.
                </p>
            </li>
            <li class="info__list">
                <span class="info__date">11 Nov 2016</span>
                <h3 class="info__heading">Enabled autoplay on mobile</h3>
                <p class="info__content">Now mobile devies will mute and autoplay the video preview.</p>
            </li>
            <li class="info__list">
                <span class="info__date">29 Oct 2016</span>
                <h3 class="info__heading">Added URL params for playback options</h3>
                <p class="info__content">You can now use URL params to control playback options, for example:</p>
                <pre class="info__pre">https://trace.moe/?autoplay=0&loop&mute=1&url=</pre>
            </li>
            <li class="info__list">
                <span class="info__date">23 Oct 2016</span>
                <h3 class="info__heading">Improved performance</h3>
                <p class="info__content">Reduced search result candidates from 10 Million to 3 Million. This would reduce accuracy but greatly improves performance.</p>
            </li>
            <li class="info__list">
                <span class="info__date">5 Oct 2016</span>
                <h3 class="info__heading">Added links to database dump</h3>
                <p class="info__content">You can now download a complete dump of the database.</p>
            </li>
            <li class="info__list">
                <span class="info__date">1 Oct 2016</span>
                <h3 class="info__heading">Added more server status</h3>
                <p class="info__content">You can see server load and recently indexed files in /about</p>
            </li>
            <li class="info__list">
                <span class="info__date">1 Oct 2016</span>
                <h3 class="info__heading">Adding Raw Anime</h3>
                <p class="info__content">The database has started indexing raw anime from now on.</p>
            </li>
            <li class="info__list">
                <span class="info__date">14 Sep 2016</span>
                <h3 class="info__heading">Added Telegram Bot</h3>
                <p class="info__content">This telegram bot can tell you where an anime screenshot is taken from. Just send / forward an image to <a href="https://telegram.me/WhatAnimeBot"">https://telegram.me/WhatAnimeBot</a>.</p>
            </li>
            <li class="info__list">
                <span class="info__date">14 Aug 2016</span>
                <h3 class="info__heading">WebExtension Changes</h3>
                <p class="info__content">The ?auto url param is no longer used. Now it would always automatically search.</p>
                <p class="info__content">WebExtension has updated. Now it would copy and paste using dataURL in background. It allows searching images from pages that's not publicly available such as Facebook Feeds. It also supports searching from HTML5 videos using the frame extracted at the moment you click it. Now extensions no longer use the ?url to send search images.</p>
            </li>
            <li class="info__list">
                <span class="info__date">14 Aug 2016</span>
                <h3 class="info__heading">WebExtension Changes</h3>
                <p class="info__content">The ?auto url param is no longer used. Now it would always automatically search.</p>
                <p class="info__content">WebExtension has updated. Now it would copy and paste using dataURL in background. It allows searching images from pages that's not publicly available such as Facebook Feeds. It also supports searching from HTML5 videos using the frame extracted at the moment you click it. Now extensions no longer use the ?url to send search images.</p>
            </li>
            <li class="info__list">
                <span class="info__date">1 Jul 2016</span>
                <h3 class="info__heading">WebExtension for Chrome, Firefox and Opera</h3>
                <p class="info__content">
                    <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp">Chrome Extension</a>,
                    <a href="https://addons.mozilla.org/en-US/firefox/addon/search-anime-by-screenshot/">Firefox Add-on</a>,
                    <a href="https://addons.opera.com/en/extensions/details/search-anime-by-screenshot/">Opera Add-on</a> has been relased.
                </p>
                <p class="info__content">Source code available on <a href="https://github.com/soruly/whatanime.ga-WebExtension">GitHub</a></p>
            </li>
            <li class="info__list">
                <span class="info__date">30 Jun 2016</span>
                <h3 class="info__heading">Improved anime title language</h3>
                <p class="info__content">It now shows anime titles according to users' browser language.</p>
            </li>
            <li class="info__list">
                <span class="info__date">30 May 2016</span>
                <h3 class="info__heading">Added loading icon</h3>
                <p class="info__content">The thumbnail may not be at the exact moment, since the seeking is not very accurate. Play the preview to see if it's what you are looking for.</p>
            </li>
            <li class="info__list">
                <span class="info__date">6 May 2016</span>
                <h3 class="info__heading">Fixed some bugs in URL loading</h3>
                <p class="info__content">In case the image cannot be loaded, upload the image from file or copy image itself (not URL) then Ctrl+V</p>
            </li>
            <li class="info__list">
                <span class="info__date">23 Apr 2016</span>
                <h3 class="info__heading">Speed up image load from URL</h3>
                <p class="info__content">Images load from URL would be compressed. This would speed up loading GIF and large images from URL.</p>
            </li>
            <li class="info__list">
                <span class="info__date">18 Apr 2016</span>
                <h3 class="info__heading">Performance Tweaks</h3>
                <p class="info__content">Server will now cache some search results. Search results would be cached for 5-30 minutes. The better the search results, the longer the results would be cached.</p>
            </li>
            <li class="info__list">
                <span class="info__date">8 Apr 2016</span>
                <h3 class="info__heading">Updated Chrome Extension</h3>
                <p class="info__content">Once the image completes loading, it would search automatically. You can change the setting in Chrome Extension. Also see how you can search in Firefox in <a href="/faq" title="FAQ">FAQ</a>.</p>
            </li>
            <li class="info__list">
                <span class="info__date">7 Apr 2016</span>
                <h3 class="info__heading">Added index cache status</h3>
                <p class="info__content">You can now see how much data is cached in RAM from About page. The higher the percentage faster the search. It usually stays around 33% due to limited RAM.</p>
            </li>
            <li class="info__list">
                <span class="info__date">7 Apr 2016</span>
                <h3 class="info__heading">Incorrect timestamp issue identified</h3>
                <p class="info__content">There has been some incorrect timestamp in search results due to image analyze scripts parsing outputs of ffmpeg incorrectly. The script has been updated now. From now on new animes should have a correct timestamp, while it would take at about two months to fix already indexed animes. The new script is also 33% faster when indexing anime.</p>
            </li>
            <li class="info__list">
                <span class="info__date">10 Mar 2016</span>
                <h3 class="info__heading">Fixes some image editing issue</h3>
                <p class="info__content">Image would sometimes gone black when clicking fit / flip button. Now fixed.</p>
            </li>
            <li class="info__list">
                <span class="info__date">6 Mar 2016</span>
                <h3 class="info__heading">Added Fit Width / Height option</h3>
                <p class="info__content">You can now choose to Fit Width / Height for your search image. Also fixed some flickering issue on previews.</p>
            </li>
            <li class="info__list">
                <span class="info__date">3 Mar 2016</span>
                <h3 class="info__heading">Fixed some Image URL issue</h3>
                <p class="info__content">Fixed some cross-site image URL linking issue. Most image URL should load now.</p>
            </li>
            <li class="info__list">
                <span class="info__date">1 Mar 2016</span>
                <h3 class="info__heading">New Anime Info Panel UI</h3>
                <p class="info__content">A better layout for more Anime information.</p>
            </li>
            <li class="info__list">
                <span class="info__date">1 Mar 2016</span>
                <h3 class="info__heading">Added Image URL Option</h3>
                <p class="info__content">You can now search by Image URL</p>
            </li>
            <li class="info__list">
                <span class="info__date">28 Feb 2016</span>
                <h3 class="info__heading">Added Safe Search Option</h3>
                <p class="info__content">The Safe Search Option can hide most Hentai Anime from search result. But you should aware that some regular season Animes can still be obscene. (NSFW)</p>
            </li>
            <li class="info__list">
                <span class="info__date">28 Feb 2016</span>
                <h3 class="info__heading">Try the Chrome Extension</h3>
                <p class="info__content">Now you can use the <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp">Chrome Extension</a> to search.</p>
            </li>
            <li class="info__list">
                <span class="info__date">28 Feb 2016</span>
                <h3 class="info__heading">Added some Heitai Anime</h3>
                <p class="info__content">About 168 Heitai Anime series has been added.</p>
            </li>
            <li class="info__list">
                <span class="info__date">28 Feb 2016</span>
                <h3 class="info__heading">Added sample screenshot in FAQ</h3>
                <p class="info__content">To help users to understand how the search engine works, we have added some good and bad screenshots in <a href="/faq">FAQ</a>.</p>
            </li>
            <li class="info__list">
                <span class="info__date">21 Feb 2016</span>
                <h3 class="info__heading">Performance Improvement</h3>
                <p class="info__content">Improved caching method to warmup cold data.</p>
            </li>
            <li class="info__list">
                <span class="info__date">20 Feb 2016</span>
                <h3 class="info__heading">More fixes</h3>
                <p class="info__content">Database has been cleaned up and reloaded. This should fix most video previews. Fixed some anime titles still being null.</p>
            </li>
            <li class="info__list">
                <span class="info__date">17 Feb 2016</span>
                <h3 class="info__heading">Bug Fix</h3>
                <p class="info__content">Fixed the empty search result. The issue has been resolved. A large number of files has been relocated, video preview may be missing for some search results.</p>
            </li>
            <li class="info__list">
                <span class="info__date">1 Jan 2016</span>
                <h3 class="info__heading">Improved Performance</h3>
                <p class="info__content">Increased cache size to improve performance when the server has been idle for a long time.</p>
            </li>
            <li class="info__list">
                <span class="info__date">31 Dec 2015</span>
                <h3 class="info__heading">Added a Flip Button</h3>
                <p class="info__content">You may now flip the image before searching. If you can't find a match, try to flip your image and search again. (Especially useful for AMV)</p>
            </li>
            <li class="info__list">
                <span class="info__date">21 Dec 2015</span>
                <h3 class="info__heading">Search Algorithm Changed</h3>
                <p class="info__content">Switched to use a new searching algorithm. The search is slower but more accurate.</p>
            </li>
            <li class="info__list">
                <span class="info__date">19 Dec 2015</span>
                <h3 class="info__heading">Public Beta</h3>
                <p class="info__content">Adding some informative pages.</p>
            </li>
        </ul>
    </div>

    <footer class="footer">
        <div class="width">
            <div class="footer__menu">
                <a class="footer__item" href="/">Home</a>
                <a class="footer__item" href="/about">About</a>
                <a class="footer__item" href="/changelog">Changelog</a>
                <a class="footer__item" href="/faq">FAQ</a>
                <a class="footer__item" href="/terms">Terms</a>
            </div>
        </div><!-- /.width -->
    </footer><!-- /.footer -->
</body>

</html> 