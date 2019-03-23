<?php
header("Link: </css/style.css>; rel=preload; as=style", false);
header("Link: </css/bootstrap.min.css>; rel=preload; as=style", false);
header("Link: </js/analytics.js>; rel=preload; as=script", false);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Search Anime by ScreenShot. Lookup the exact moment and the episode.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#60738b" />
    <title>WAIT: What Anime Is This? - Frequently Asked Questions</title>
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
                <li><a href="/changelog">Changelog</a></li>
                <li><a href="/faq" class="active">FAQ's</a></li>
                <li><a href="/terms">Terms</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <ul class="wait-info__group">
            <li>
                <h2>Frequently Asked Questions</h2>
            </li>
            <?php /* Notes 
                <li>
                    <h3>Heading</h3>
                    <h4>Sub Heading</h4>
                    <div>
                        <p>Content</p>
                        <p><img/></p>
                        <ol>...</ol>
                        <pre>...</pre>
                    </div>
                </li>

                Screenshot Compare:
                    <div class="wait-screenshot__compare">
                        <div><img alt="" src="/img/###-###.jpg"><span>### Screenshot</span></div>
                        <div><img alt="" src="/img/###-###.jpg"><span>### Screenshot</span></div>
                    </div>

                if you have any questions or clarifications you can message me anytime
            */ ?>
            <li>
                <h3>Search is not working!</h3>
                <div>
                    <p>Some anti-virus / internet security software such as kaspersky may have blocked the website. You need to whitelist the website.</p>
                </div>
            </li>
            <li>
                <h3>Cannot open the website!</h3>
                <div>
                    <p>Some browser does not support latest version of SSL/TLS, such as 360 Browser in China. Try another browser. Primary supported browsers are Chrome and Firefox.</p>
                </div>
            </li>
            <li>
                <h3>Why I can't find the search result?</h3>
                <div>
                    <p>Possible reasons:</p>
                    <ol>
                        <li>
                            <p>Your image is not an original anime screenshot.</p>
                            <p>Answer: You may try to use <a href="https://saucenao.com/">SauceNAO</a> and <a href="https://iqdb.org/">iqdb.org</a> which is best for searching anime artwork.</p>
                        </li>
                        <li>
                            <p>The anime has not been analyzed yet.</p>
                            <p>Answer: New animes currently airing would be analyzed around 24 hours after TV broadcast. Long-running animes / cartoons are excluded at this stage. See "<a href="#waarbi?">What animes are being indexed?</a>" at the bottom of this page.</p>
                        </li>
                        <li>
                            <p>Your image is flipped.</p>
                            <p>Answer: If you image comes from AMV / Anime Compilations, it's likely its flipped horizontally. Use the flip button to search again.</p>
                        </li>
                        <li>
                            <p>Your image is of bad quality.</p>
                            <p>Answer: The image search algorithm is designed for almost-exact match, not similar match. It analyze the color layout of the image. So, when your image is not a full un-cropped original 16:9 screenshot (i.e. cropped image), the search would likely fail.</p>
                        </li>
                    </ol>
                    <p>Color is an important factor for the correct search, if heavy tints and filters are applied to the screenshot (i.e. grayscale, contrast, saturate, brightness, sepia), too much information are lost. In this case the search would also fail. The Edge Histogram can solve this issue by ignoring colors and only search edges. But I am running out of computing resource to support another image descriptor.</p>
                    <p>Image transform is also an important factor. If the image is not scaled without maintaining original aspect ratios (i.e. elongated, flipped, rotated), the search would also fail.</p>
                    <p>Text occupied too much of the image. Large texts on the image would interfere the original image. The system is not smart enough to ignore the text.</p>
                    <p>If you image has too little distinguish features (e.g. dark images or images with large plain blocks of plain colors), the search would also fail.</p>
                    <p>Searching with a real photo (of an anime) definitely won't work.</p>
                </div>
            </li>
            <li>
                <h3>Examples of bad screenshots</h3>
                <h4>Extra border added</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/border-bad.jpg)"></span><span>Bad Screenshot</span></div>
                    <div><span style="background-image: url(/img/border-good.jpg)"></span><span>Good Screenshot</span></div>
                </div>
                <div>
                    <p>In case your screenshot has extra borders, please trim off the extra borders before you search.</p>
                </div><br>
                <h4>Cropped Image</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/cropped-bad.jpg)"></span><span>Bad Screenshot</span></div>
                    <div><span style="background-image: url(/img/cropped-good.jpg)"></span><span>Good Screenshot</span></div>
                </div>
                <div>
                    <p>Cropping the image would result a huge loss of information content.</p>
                </div><br>
                <h4>Flipped image</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/flipped-bad.jpg)"></span><span>Bad Screenshot</span></div>
                    <div><span style="background-image: url(/img/flipped-good.jpg)"></span><span>Good Screenshot</span></div>
                </div>
                <div>
                    <p>This screenshot from <a href="https://www.youtube.com/watch?v=TUoWYoTWcnA&feature=youtu.be&t=2m59s">AMV - Animegraphy 2015</a> flipped the original scene in the anime. Try to search with the flip button if you guess the image has been flipped.</p>
                </div><br>
                <h4>Tinted image</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/tinted-bad.jpg)"></span><span>Bad Screenshot</span></div>
                    <div><span style="background-image: url(/img/tinted-good.jpg)"></span><span>Good Screenshot</span></div>
                </div>
                <div>
                    <p>Tinted images are hard to search. Because the applied filter effects heavily distorted the information in the original screenshot. The color layout image descriptor can no longer find such images.</p>
                </div><br>
                <h4>Old Japanese Anime</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/old-bad.jpg)"></span><span>Sample Screenshot</span></div>
                </div>
                <div>
                    <p>Anime of this age are not indexed.</p>
                </div><br>
                <h4>Not from Anime Screenshot</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/notanime-bad.jpg)"></span><span>Sample Screenshot</span></div>
                </div>
                <div>
                    <p>You should try <a href="https://saucenao.com/">SauceNAO</a> and <a href="https://iqdb.org/">https://iqdb.org/</a> to search anime / doujin artwork.</p>
                </div><br>
                <h4>Not Japanese Anime</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/nonjapanese-bad.jpg)"></span><span>Sample Screenshot</span></div>
                </div>
                <div>
                    <p>Tom and Jerry is obviously not a Japanese Anime.</p>
                </div><br>
                <h4>Dark image</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/dark-bad.jpg)"></span><span>Sample Screenshot</span></div>
                </div>
                <div>
                    <p>Dark images are hard to distinguish using the color layout descriptor.</p>
                </div><br>
                <h4>Low resolution image</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/lowres-bad.jpg)"></span><span>Sample Screenshot</span></div>
                </div>
                <div>
                    <p>Your image should be at least 320 x 180 pixels to search effectively.</p>
                </div>
            </li>
            <li>
                <h3>Examples of acceptable screenshots</h3>
                <h4>Slightly distorted size</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/distorted-bad.jpg)"></span><span>Acceptable Screenshot</span></div>
                    <div><span style="background-image: url(/img/distorted-good.jpg)"></span><span>Original Screenshot</span></div>
                </div>
                <div>
                    <p>In case your screenshot has extra borders, please trim off the extra borders before you search.</p>
                </div><br>
                <h4>Reasonably sized subtitles</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/subtitles-bad.jpg)"></span><span>Acceptable Screenshot</span></div>
                    <div><span style="background-image: url(/img/subtitles-good.jpg)"></span><span>Original Screenshot</span></div>
                </div><div><p></p></div><br>
                <h4>A frame of GIF</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/gif-bad.jpg)"></span><span>Acceptable Screenshot</span></div>
                    <div><span style="background-image: url(/img/gif-good.jpg)"></span><span>Original Screenshot</span></div>
                </div>
                <div>
                    <p>If the color distortion is acceptable, GIF is also OK.</p>
                </div><br>
                <h4>Drawings of the anime scene</h4>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/draw-bad.jpg)"></span><span>Acceptable Screenshot</span></div>
                    <div><span style="background-image: url(/img/draw-good.jpg)"></span><span>Original Screenshot</span></div>
                </div>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/draw2-bad.jpg)"></span><span>Acceptable Screenshot</span></div>
                    <div><span style="background-image: url(/img/draw2-good.jpg)"></span><span>Original Screenshot</span></div>
                </div>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/draw3-bad.jpg)"></span><span>Acceptable Screenshot</span></div>
                    <div><span style="background-image: url(/img/draw3-good.jpg)"></span><span>Original Screenshot</span></div>
                </div>
                <div class="wait-screenshot__compare">
                    <div><span style="background-image: url(/img/draw4-bad.jpg)"></span><span>Acceptable Screenshot</span></div>
                    <div><span style="background-image: url(/img/draw4-good.jpg)"></span><span>Original Screenshot</span></div>
                </div>
                <div>
                    <p>The search image does not has to be taken from anime screencap directly. You can use drawings of some scenes as long as it is similar to the original one.</p>
                </div>
            </li>
            <li>
                <h3>How does it work?</h3>
                <div>
                    <p>It uses the MPEG 7 Color Layout Descriptor for comparing images. Wikipedia has a good illustration about it. This would also explain why you can't find your image. <a href="https://en.wikipedia.org/wiki/Color_layout_descriptor">https://en.wikipedia.org/wiki/Color_layout_descriptor</p></a>
                </div>
            </li>
            <li>
                <h3>How to make the search for a more accurate result?</h3>
                <div>
                    <p>Crop your screenshot to 16:9 or 4:3 before searching. Remove any extra borders in screencap (if any). By default, it crops the image to 16:9, if you upload a 16:10 screenshot, it should be cropped automatically. If the position is incorrect, you can drag the image and adjust the crop position.</p>
                </div>
            </li>
            <li id="waarbi?">
                <h3>What animes are being indexed?</h3>
                <div>
                    <p>Most japan anime since 2000 are indexed, plus some popular anime in 1990s, and little anime before 1990. A list of anime are incomplete in index at this stage, including Jewelpet, Yu-Gi-Oh!, Dragon Ball, Crayon Shin-chan, Doraemon, Pokemon, Detective Conan, Chibi Maruko-chan and old Hentai-anime.</p>
                </div>
            </li>
            <li>
                <h3>Why I can't preview the search result?</h3>
                <div>
                    <p>There is no preview for mobile devices. Some animes are being removed or relocated, so some of the previews are offline now. The preview uses a considerable amount of network bandwidth, it would takes time to load if you have a slow connection.</p>
                </div>
            </li>
            <li>
                <h3>Why the anime are chinese-subbed?</h3>
                <div>
                    <p>I am still collecting raw animes, and it would take a number of powerful servers several months to complete. It will switch to the new dataset once it is ready. The current dataset uses chinese-subbed anime because the current index is provided by some Asian users.</p>
                </div>
            </li>
            <li>
                <h3>How can I watch the entire anime?</h3>
                <div>
                    <p>This website is not intended for watching anime.</p>
                    <p>If you wish to watch the anime, you may check which TV channel is broadcasting the anime in you country. For those which has finished airing, consider buying or renting the original Blu-ray/DVDs.</p>
                </div>
            </li>
            <li>
                <h3>How can I share the search result?</h3>
                <div>
                    <p>For now, you have to upload the image somewhere else, then search by image URL, and share the result URL. If you have any feedback or suggestions, feel free to contact <a href="mailto:help@trace.moe">help@trace.moe</a>.</p>
                </div>
            </li>
            <li>
                <h3>How to add trace.moe to Image Search Options</h3>
                <div>
                    <p>If you prefer to use trace.moe with <a href="https://chrome.google.com/webstore/detail/image-search-options/kljmejbpilkadikecejccebmccagifhl">Image Search Options</a>, go to settings and add this:</p>
                    <pre>https://trace.moe/?url=</pre>
                    <p>You can also configure playback options via URL params:</p>
                    <pre>https://trace.moe/?autoplay=0&loop&mute=1&url=</pre>
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
                <li><a href="/faq">FAQ's</a></li>
                <li><a href="/terms">Terms</a></li>
            </ol>
        </div>
    </footer>
</body>

</html> 