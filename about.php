<?php
header("Link: </css/app.css>; rel=preload; as=style", false);
header("Link: </css/bootstrap.min.css>; rel=preload; as=style", false);
header("Link: </js/analytics.js>; rel=preload; as=script", false);

ini_set("display_errors", 0);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://192.168.2.12:8983/solr/admin/cores?wt=json");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($curl);
$result = json_decode($res);
curl_close($curl);

function humanTiming($time)
{
    $time = time() - $time; // to get the time since that moment
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
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
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
    }
}
$numDocs = 0;
$sizeInBytes = 0;
$lastModified = 0;
foreach ($result->status as $core) {
    $numDocs += $core->index->numDocs;
    $sizeInBytes += $core->index->sizeInBytes;
    if (strtotime($core->index->lastModified) > $lastModified) {
        $lastModified = strtotime($core->index->lastModified);
    }
}
$numDocsMillion = floor($numDocs / 1000000);
$sizeInGB = floor($sizeInBytes / 1073741824);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- (๑・ω・๑) -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="description" content="Search Anime by ScreenShot. Lookup the exact moment and the episode.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#60738b" />
    <title>WAIT: What Anime Is This? - About</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="icon" type="image/png" href="/favicon128.png" sizes="128x128">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <script src="/js/analytics.js" defer></script>
</head>

<body class="about">
    <nav class="navbar">
        <div class="width">
            <a href="javascript:void(0);" class="navbar__hamburger">
                <span class="glyphicon glyphicon-menu-hamburger"></span>
            </a>
            <div class="navbar__menu">
                <a class="navbar__item" href="/" >Home</a>
                <a class="navbar__item--active" href="/about">About</a>
                <a class="navbar__item" href="/changelog">Changelog</a>
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
                <h2 class="info__title">About</h2>
            </li>
            <li class="info__list">
                <p class="info__content">
                    Life is too short to answer all the "What Anime Is This?" questions.
                    Let computers do that for you.
                </p>
                <p class="info__content">
                    trace.moe is a test-of-concept prototype search engine that helps users trace back the original anime by screenshot.
                    It searches over 22300 hours of anime and find the best matching scene.
                    It tells you what anime it is, from which episode and the time that scene appears.
                    Since the search result may not be accurate, it provides a few seconds of preview for verification.
                </p>
                <p class="info__content">
                    There has been a lot of anime screencaps and GIFs spreading around the internet, but very few of them mention the source.
                    While those online platforms are gaining popularity, trace.moe respects the original producers and staffs by showing interested anime fans what the original source is.
                    This search engine encourages users to give credits to the original creater / owner before they share stuff online.
                </p>
                <p class="info__content">
                    This website is non-profit making. There is no pro/premium features at all.
                    This website is not intended for watching anime. The server has effective measures to forbid users to access the original video beyond the preview limit. I would like to redirect users to somewhere they can watch that anime legally, if possible.
                </p>
                <p class="info__content">
                    Most Anime since 2000 are indexed, but some are excluded (see <a href="/faq" class="info__content-link" title="FAQ">FAQ</a>).
                    No Doujin work, no derived art work are indexed. The system only analyzes officially published anime.
                    If you wish to search artwork / wallpapers, try to use <a href="https://saucenao.com/" class="info__content-link" title="SauceNAO">SauceNAO</a> and <a href="https://iqdb.org/" class="info__content-link" title="iqdb.org">iqdb.org</a>
                </p>
            </li>
        </ul><!-- /.info -->
        <ul class="info">
            <li class="info__list">
                <h2 class="info__title">WebExtension</h2>
            </li>
            <li class="info__list">
                <p class="info__content">
                    WebExtension available for 
                    <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp" class="info__content-link" title="Chrome">Chrome</a>,
                    <a href="https://addons.mozilla.org/en-US/firefox/addon/search-anime-by-screenshot/" class="info__content-link" title="Firefox">Firefox</a>, or
                    <a href="https://addons.opera.com/en/extensions/details/search-anime-by-screenshot/" class="info__content-link" title="Opera">Opera</a> to search.
                </p>
                <p class="info__content">Source code and user guide on Github: <a href="https://github.com/soruly/trace.moe-WebExtension" class="info__content-link" title="trace.moe WebExtension Source">trace.moe WebExtension Source</a></p>
            </li>
        </ul><!-- /.info -->
        <ul class="info">
            <li class="info__list">
                <h2 class="info__title">Telegram Bot</h2>
            </li>
            <li class="info__list">
                <div>
                    <p class="info__content">Telegram Bot available <a href="https://telegram.me/WhatAnimeBot" class="info__content-link" title="@WhatAnimeBot">@WhatAnimeBot</a></p>
                    <p class="info__content">Source code and user guide on Github: <a href="https://github.com/soruly/trace.moe-telegram-bot" class="info__content-link" title="trace.moe Telegram Bot Source">trace.moe Telegram Bot Source</a></p>
                </div>
            </li>
        </ul><!-- /.info -->
        <ul class="info">
            <li class="info__list">
                <h2 class="info__title">Official API (Beta)</h2>
            </li>
            <li class="info__list">
                <p class="info__content">Official API Docs available at <a href="https://soruly.github.io/trace.moe/#/" class="info__content-link" title="trace.moe GitHub">trace.moe GitHub</a></p>
            </li>
        </ul><!-- /.info -->
        <ul class="info">
            <li class="info__list">
                <h2 class="info__title">Mobile Applications</h2>
            </li>
            <li class="info__list">
                <!-- WhatAnime by Andrée Torres -->
                <h4 class="info__subheading">WhatAnime by Andrée Torres</h4>
                <p class="info__content">Download: <a href="https://play.google.com/store/apps/details?id=com.maddog05.whatanime" class="info__content-link" title="https://play.google.com/store/apps/details?id=com.maddog05.whatanime">https://play.google.com/store/apps/details?id=com.maddog05.whatanime</a></p>
                <p class="info__content">Source: <a href="https://github.com/maddog05/whatanime-android" class="info__content-link" title="https://github.com/maddog05/whatanime-android">https://github.com/maddog05/whatanime-android</a></p>
                <!-- WhatAnime - 以图搜番 by Mystery0 (Simplified Chinese) -->
                <h4 class="info__subheading">WhatAnime - 以图搜番 by Mystery0 (Simplified Chinese)</h4>
                <p class="info__content">Download: <a href="https://play.google.com/store/apps/details?id=pw.janyo.whatanime" class="info__content-link" title="https://play.google.com/store/apps/details?id=pw.janyo.whatanime">https://play.google.com/store/apps/details?id=pw.janyo.whatanime</a></p>
                <p class="info__content">Source: <a href="https://github.com/JanYoStudio/WhatAnime" class="info__content-link" title="https://github.com/JanYoStudio/WhatAnime">https://github.com/JanYoStudio/WhatAnime</a></p>
            </li>
        </ul><!-- /.info -->
        <ul class="info">
            <li class="info__list">
                <h2 class="info__title">Presentation slides</h2>
            </li>
            <li class="info__list">
                <p class="info__content"><a href="https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga.slide" class="info__content-link" title="Go-talk presentation on 27 May 2016">Go-talk presentation on 27 May 2016</a></p>
                <p class="info__content"><a href="https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga-2017.slide" class="info__content-link" title="Go-talk presentation on 4 Jun 2016">Go-talk presentation on 4 Jun 2017</a></p>
                <p class="info__content"><a href="https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga-2018.slide" class="info__content-link" title="Go-talk presentation on 17 Jun 2016">Go-talk presentation on 17 Jun 2018</a></p>
            </li>
        </ul><!-- /.info -->
        <ul class="info">
            <li class="info__list">
                <h2 class="info__title">System Status</h2>
            </li>
            <li class="info__list">
                <p class="info__content">System status page: <a href="https://status.trace.moe" class="info__content-link" title="https://status.trace.moe">https://status.trace.moe</a> (Powered by UptimeRobot)</p>
                <p class="info__content"><?php echo 'Last Database Index update: ' . humanTiming($lastModified) . ' ago with ' . $numDocsMillion . ' Million analyzed frames. (' . $sizeInGB . ' GB)'; ?></p>
                <p class="info__content">This database automatically index most airing anime in a few hours after broadcast.</p>
                <p class="info__content">You may subscribe to the updates on Telegram <a href="https://t.me/whatanimeupdates" class="info__content-link" title="@whatanimeupdates">@whatanimeupdates</a></p>
                <p class="info__content"><a href="https://nyaa.si/download/1023979.torrent" class="info__content-link" title="Full Database Dump 2018-04 (16.5GB)">Full Database Dump 2018-04 (16.5GB)</a></p>
                <p class="info__content"><a href="magnet:?xt=urn:btih:VUOXSGHJ5CSBP6C3KK4IZCFS7NCVXPKX&dn=whatanime.ga+database+dump+2018-04&tr=http%3A%2F%2Fnyaa.tracker.wf%3A7777%2Fannounce&tr=udp%3A%2F%2Fopen.stealth.si%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969%2Fannounce" class="info__content-link" title="magnet:?xt=urn:btih:VUOXSGHJ5CSBP6C3KK4IZCFS7NCVXPKX">magnet:?xt=urn:btih:VUOXSGHJ5CSBP6C3KK4IZCFS7NCVXPKX</a></p>
            </li>
        </ul><!-- /.info -->
        <ul class="info">
            <li class="info__list">
                <h2 class="info__title">Contact</h2>
            </li>
            <li class="info__list">
                <p class="info__content">If you have any feedback, suggestions or anything else, please email to <a href="mailto:help@trace.moe" class="info__content-link" title="help@trace.moe">help@trace.moe</a>.</p>
                <p class="info__content">You may also reach the author on Telegram <a href="https://t.me/soruly" class="info__content-link" title="@soruly">@soruly</a> or <a href="https://discord.gg/K9jn6Kj" class="info__content-link" title="Discord">Discord</a>.</p>
                <p class="info__content">
                    Follow the development of trace.moe and learn more about the underlying technologies on 
                    <a href="https://github.com/soruly/slides" class="info__content-link" title="GitHub">GitHub</a>, 
                    <a href="https://www.facebook.com/whatanime.ga/" class="info__content-link" title="Facebook Page">Facebook Page</a> or 
                    <a href="https://www.patreon.com/soruly" class="info__content-link" title="Patreon Page">Patreon page</a>.
                </p>
            </li>
        </ul><!-- /.info -->
        <ul class="info">
            <li class="info__list">
                <h2 class="info__title">Credits</h2>
            </li>
            <li class="info__list">
                <h4 class="info__subheading">Dr. Mathias Lux (<a href="http://www.lire-project.net/" class="info__content-link" title="LIRE Project">LIRE Project</a>)</h4>
                <p class="info__content">
                    Lux Mathias, Savvas A. Chatzichristofis. Lire: Lucene Image Retrieval – An Extensible Java CBIR Library. 
                    In proceedings of the 16th ACM International Conference on Multimedia, pp. 1085-1088, Vancouver, Canada, 2008
                    <a href="http://www.morganclaypool.com/doi/abs/10.2200/S00468ED1V01Y201301ICR025" class="info__content-link" title="Visual Information Retrieval with Java and LIRE">Visual Information Retrieval with Java and LIRE</a>
                </p>
                <h4 class="info__subheading">Josh (<a href="https://anilist.co/" class="info__content-link" title="Anilist">Anilist</a>) and Anilist team</h4>
            </li>
        </ul><!-- /.info -->
        <ul class="info">
            <li class="info__list">
                <h2 class="info__title">Donate</h2>
            </li>
            <li class="info__list">
                <div>
                    <p class="info__content">
                        <a href="https://www.paypal.me/soruly" class="info__donate-link" title="soruly PayPal">
                            <img class="info__donate-image" src="img/donate-with-paypal.png" alt="Donate with PayPal" />
                        </a>
                        <a href="https://www.patreon.com/soruly" class="info__donate-link" title="soruly Patreon">
                            <img class="info__donate-image" src="img/become_a_patron_button.png" alt="Become a Patron!" />
                        </a>
                    </p>
                    <p class="info__content">Former and Current Supporters:</p>
                    <ul class="info__unordered-list">
                        <li><p class="info__content"><a href="https://chenxublog.com" class="info__content-link" title="chenxuuu">chenxuuu</a></p></li>
                        <li><p class="info__content"><a href="http://desmonding.me/" class="info__content-link" title="Desmond">Desmond</a></p></li>
                        <li><p class="info__content"><a href="http://imvery.moe/" class="info__content-link" title="FangzhouL">FangzhouL</a></p></li>
                        <li><p class="info__content"><a href="https://fym.moe/" class="info__content-link" title="FiveYellowMice">FiveYellowMice</a></p></li>
                        <li><p class="info__content">Snadzies</p></li>
                        <li><p class="info__content"><a href="https://taw.moe" class="info__content-link" title="thatanimeweirdo">thatanimeweirdo</a></p></li>
                        <li><p class="info__content">WelkinWill</p></li>
                        <li><p class="info__content"><a href="https://twitter.com/yuriks" class="info__content-link" title="yuriks">yuriks</a></p></li>
                        <li><p class="info__content">...and dozens of anonymous donators</p></li>
                    </ul>
                    <p class="info__content">And of course, contributions and support from all anime fans!</p>
                </div>
            </li>
        </ul><!-- /.info -->
    </div><!-- /.width -->

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