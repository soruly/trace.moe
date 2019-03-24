<?php
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
    <meta name="theme-color" content="#60738b" />
    <title>WAIT: What Anime Is This? - Frequently Asked Questions</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="icon" type="image/png" href="/favicon128.png" sizes="128x128">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <script src="/js/analytics.js" defer></script>
</head>

<body class="faq">
    <nav class="navbar">
        <div class="width">
            <a href="javascript:void(0);" class="navbar__hamburger">
                <span class="glyphicon glyphicon-menu-hamburger"></span>
            </a>
            <div class="navbar__menu">
                <a class="navbar__item" href="/" >Home</a>
                <a class="navbar__item" href="/about">About</a>
                <a class="navbar__item" href="/changelog">Changelog</a>
                <a class="navbar__item--active" href="/faq">FAQ</a>
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
                <h2 class="info__title">Frequently Asked Questions</h2>
            </li>
            <li class="info__list">
                <!-- Search is not working! -->
                <h3 class="info__heading">Search is not working!</h3>
                <p class="info__content">Some anti-virus / internet security software such as kaspersky may have blocked the website. You need to whitelist the website.</p>
            </li>
            <li class="info__list">
                <!-- Cannot open the website! -->
                <h3 class="info__heading">Cannot open the website!</h3>
                <p class="info__content">Some browser does not support latest version of SSL/TLS, such as 360 Browser in China. Try another browser. Primary supported browsers are Chrome and Firefox.</p>
            </li>
            <li class="info__list">
                <!-- Why I can't find the search result? -->
                <h3 class="info__heading">Why I can't find the search result?</h3>
                <p class="info__content">Possible reasons:</p>
                <ol class="info__ordered-list">
                    <li>
                        <p class="info__content">
                            <strong>Your image is not an original anime screenshot.</strong>
                            You may try to use <a href="https://saucenao.com/" class="info__content-link" title="SauceNAO">SauceNAO</a> and <a href="https://iqdb.org/" class="info__content-link" title="iqdb.org">iqdb.org</a> which is best for searching anime artwork.
                        </p>
                    </li>
                    <li>
                        <p class="info__content">
                            <strong>The anime has not been analyzed yet.</strong>
                            New animes currently airing would be analyzed around 24 hours after TV broadcast. Long-running animes / cartoons are excluded at this stage. See "<a href="#waarbi?" class="info__content-link" title="What animes are being indexed?">What animes are being indexed?</a>" at the bottom of this page.
                        </p>
                    </li>
                    <li>
                        <p class="info__content">
                            <strong>Your image is flipped.</strong>
                            If your image comes from AMV / Anime Compilations, it's likely its flipped horizontally. Use the flip button to search again.
                        </p>
                    </li>
                    <li>
                        <p class="info__content">
                            <strong>Your image is of bad quality.</strong>
                            The image search algorithm is designed for almost-exact match, not similar match. It analyze the color layout of the image. So, when your image is not a full un-cropped original 16:9 screenshot (i.e. cropped image), the search would likely fail.
                        </p>
                    </li>
                </ol>
                <p class="info__content">Color is an important factor for the correct search, if heavy tints and filters are applied to the screenshot (i.e. grayscale, contrast, saturate, brightness, sepia), too much information are lost. In this case the search would also fail. The Edge Histogram can solve this issue by ignoring colors and only search edges. But I am running out of computing resource to support another image descriptor.</p>
                <p class="info__content">Image transform is also an important factor. If the image is not scaled without maintaining original aspect ratios (i.e. elongated, flipped, rotated), the search would also fail.</p>
                <p class="info__content">Text occupied too much of the image. Large texts on the image would interfere the original image. The system is not smart enough to ignore the text.</p>
                <p class="info__content">If you image has too little distinguish features (e.g. dark images or images with large plain blocks of plain colors), the search would also fail.</p>
                <p class="info__content">Searching with a real photo (of an anime) definitely won't work.</p>
            </li>
            <li class="info__list">
                <!-- Examples of bad screenshot -->
                <h3 class="info__heading">Examples of bad screenshots</h3>
                <!-- Extra border added -->
                <h4 class="info__subheading">Extra border added</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/border-bad.jpg)"></span>
                        <span class="screenshot__title">Bad Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/border-good.jpg)"></span>
                        <span class="screenshot__title">Good Screenshot</span>
                    </div>
                </div>
                <p class="info__content">In case your screenshot has extra borders, please trim off the extra borders before you search.</p>
                <!-- Cropped image -->
                <h4 class="info__subheading">Cropped image</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/cropped-bad.jpg)"></span>
                        <span class="screenshot__title">Bad Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/cropped-good.jpg)"></span>
                        <span class="screenshot__title">Good Screenshot</span>
                    </div>
                </div>
                <p class="info__content">Cropping the image would result a huge loss of information content.</p>
                <!-- Flipped image -->
                <h4 class="info__subheading">Flipped image</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/flipped-bad.jpg)"></span>
                        <span class="screenshot__title">Bad Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/flipped-good.jpg)"></span>
                        <span class="screenshot__title">Good Screenshot</span>
                    </div>
                </div>
                <p class="info__content">This screenshot from <a href="https://www.youtube.com/watch?v=TUoWYoTWcnA&feature=youtu.be&t=2m59s" class="info__content-link" title="AMV - Animegraphy 2015">AMV - Animegraphy 2015</a> flipped the original scene in the anime. Try to search with the flip button if you guess the image has been flipped.</p>
                <!-- Tinted image -->
                <h4 class="info__subheading">Tinted image</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/tinted-bad.jpg)"></span>
                        <span class="screenshot__title">Bad Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/tinted-good.jpg)"></span>
                        <span class="screenshot__title">Good Screenshot</span>
                    </div>
                </div>
                <p class="info__content">Tinted images are hard to search. Because the applied filter effects heavily distorted the information in the original screenshot. The color layout image descriptor can no longer find such images.</p>
                <!-- Old Japanese Anime -->
                <h4 class="info__subheading">Old Japanese Anime</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/old-bad.jpg)"></span>
                        <span class="screenshot__title">Sample Screenshot</span>
                    </div>
                </div>
                <p class="info__content">Anime of this age are not indexed.</p>
                <!-- Not from Anime Screenshot -->
                <h4 class="info__subheading">Not from Anime Screenshot</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/notanime-bad.jpg)"></span>
                        <span class="screenshot__title">Sample Screenshot</span>
                    </div>
                </div>
                <p class="info__content">You should try <a href="https://saucenao.com/" class="info__content-link" title="SauceNAO">SauceNAO</a> and <a href="https://iqdb.org/" class="info__content-link" title="iqdb.org">https://iqdb.org/</a> to search anime / doujin artwork.</p>
                <!-- Not Japanese Anime -->
                <h4 class="info__subheading">Not Japanese Anime</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/nonjapanese-bad.jpg)"></span>
                        <span class="screenshot__title">Sample Screenshot</span>
                    </div>
                </div>
                <p class="info__content">Tom and Jerry is obviously not a Japanese Anime.</p>
                <!-- Dark image -->
                <h4 class="info__subheading">Dark image</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/dark-bad.jpg)"></span>
                        <span class="screenshot__title">Sample Screenshot</span>
                    </div>
                </div>
                <p class="info__content">Dark images are hard to distinguish using the color layout descriptor.</p>
                <!-- Low reso image -->
                <h4 class="info__subheading">Low resolution image</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/lowres-bad.jpg)"></span>
                        <span class="screenshot__title">Sample Screenshot</span></div>
                </div>
                <p class="info__content">Your image should be at least 320 x 180 pixels to search effectively.</p>
            </li>
            <li class="info__list">
                <!-- Examples of acceptable screenshots -->
                <h3 class="info__heading">Examples of acceptable screenshots</h3>
                <!-- Slightly distorted size -->
                <h4 class="info__subheading">Slightly distorted size</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/distorted-bad.jpg)"></span>
                        <span class="screenshot__title">Acceptable Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/distorted-good.jpg)"></span>
                        <span class="screenshot__title">Original Screenshot</span>
                    </div>
                </div>
                <p class="info__content">In case your screenshot has extra borders, please trim off the extra borders before you search.</p>
                <!-- Reasonably sized subtitles -->
                <h4 class="info__subheading">Reasonably sized subtitles</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/subtitles-bad.jpg)"></span>
                        <span class="screenshot__title">Acceptable Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/subtitles-good.jpg)"></span>
                        <span class="screenshot__title">Original Screenshot</span>
                    </div>
                </div>
                <!-- <p class="info__content">Reasonably sized subtitles</p> -->
                <!-- A frame of GIF -->
                <h4 class="info__subheading">A frame of GIF</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/gif-bad.jpg)"></span>
                        <span class="screenshot__title">Acceptable Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/gif-good.jpg)"></span>
                        <span class="screenshot__title">Original Screenshot</span>
                    </div>
                </div>
                <p class="info__content">If the color distortion is acceptable, GIF is also OK.</p>
                <!-- Drawings of the anime scene -->
                <h4 class="info__subheading">Drawings of the anime scene</h4>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/draw-bad.jpg)"></span>
                        <span class="screenshot__title">Acceptable Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/draw-good.jpg)"></span>
                        <span class="screenshot__title">Original Screenshot</span>
                    </div>
                </div>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/draw2-bad.jpg)"></span>
                        <span class="screenshot__title">Acceptable Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/draw2-good.jpg)"></span>
                        <span class="screenshot__title">Original Screenshot</span>
                    </div>
                </div>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/draw3-bad.jpg)"></span>
                        <span class="screenshot__title">Acceptable Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/draw3-good.jpg)"></span>
                        <span class="screenshot__title">Original Screenshot</span>
                    </div>
                </div>
                <div class="screenshot">
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/draw4-bad.jpg)"></span>
                        <span class="screenshot__title">Acceptable Screenshot</span>
                    </div>
                    <div class="screenshot__compare">
                        <span class="screenshot__image" style="background-image: url(/img/draw4-good.jpg)"></span>
                        <span class="screenshot__title">Original Screenshot</span>
                    </div>
                </div>
                <p class="info__content">The search image does not has to be taken from anime screencap directly. You can use drawings of some scenes as long as it is similar to the original one.</p>
            </li>
            <li class="info__list">
                <!-- How does it work? -->
                <h3 class="info__heading">How does it work?</h3>
                <p class="info__content">It uses the MPEG 7 Color Layout Descriptor for comparing images. Wikipedia has a good illustration about it. This would also explain why you can't find your image. <a href="https://en.wikipedia.org/wiki/Color_layout_descriptor" class="info__content-link" title="Color layout descriptor">Color layout descriptor</a></p>
            </li>
            <li class="info__list">
                <!-- How to make search for a more accurate result? -->
                <h3 class="info__heading">How to make the search for a more accurate result?</h3>
                <p class="info__content">Crop your screenshot to 16:9 or 4:3 before searching. Remove any extra borders in screencap (if any). By default, it crops the image to 16:9, if you upload a 16:10 screenshot, it should be cropped automatically. If the position is incorrect, you can drag the image and adjust the crop position.</p>
            </li>
            <li class="info__list" id="waarbi?">
                <!-- What animes are being indexed? -->
                <h3 class="info__heading">What animes are being indexed?</h3>
                <p class="info__content">Most japan anime since 2000 are indexed, plus some popular anime in 1990s, and little anime before 1990. A list of anime are incomplete in index at this stage, including Jewelpet, Yu-Gi-Oh!, Dragon Ball, Crayon Shin-chan, Doraemon, Pokemon, Detective Conan, Chibi Maruko-chan and old Hentai-anime.</p>
            </li>
            <li class="info__list">
                <!-- Why I can't preview the search result? -->
                <h3 class="info__heading">Why I can't preview the search result?</h3>
                <p class="info__content">There is no preview for mobile devices. Some animes are being removed or relocated, so some of the previews are offline now. The preview uses a considerable amount of network bandwidth, it would takes time to load if you have a slow connection.</p>
            </li>
            <li class="info__list">
                <!-- Why the anime are chinese-subbed? -->
                <h3 class="info__heading">Why the anime are chinese-subbed?</h3>
                <p class="info__content">I am still collecting raw animes, and it would take a number of powerful servers several months to complete. It will switch to the new dataset once it is ready. The current dataset uses chinese-subbed anime because the current index is provided by some Asian users.</p>
            </li>
            <li class="info__list">
                <!-- How can I watch the entire anime? -->
                <h3 class="info__heading">How can I watch the entire anime?</h3>
                <p class="info__content">This website is not intended for watching anime.</p>
                <p class="info__content">If you wish to watch the anime, you may check which TV channel is broadcasting the anime in you country. For those which has finished airing, consider buying or renting the original Blu-ray/DVDs.</p>
            </li>
            <li class="info__list">
                <!-- How can I share the search result? -->
                <h3 class="info__heading">How can I share the search result?</h3>
                <p class="info__content">For now, you have to upload the image somewhere else, then search by image URL, and share the result URL. If you have any feedback or suggestions, feel free to contact <a href="mailto:help@trace.moe" class="info__content-link" title="help@trace.moe">help@trace.moe</a>.</p>
            </li>
            <li class="info__list">
                <!-- How to add trace.moe to Image Search Options -->
                <h3 class="info__heading">How to add trace.moe to Image Search Options</h3>
                <p class="info__content">If you prefer to use trace.moe with <a href="https://chrome.google.com/webstore/detail/image-search-options/kljmejbpilkadikecejccebmccagifhl" class="info__content-link" title="Image Search Options">Image Search Options</a>, go to settings and add this:</p>
                <pre class="info__pre">https://trace.moe/?url=</pre>
                <p class="info__content">You can also configure playback options via URL params:</p>
                <pre class="info__pre">https://trace.moe/?autoplay=0&loop&mute=1&url=</pre>
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