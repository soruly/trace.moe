<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>WAIT: What Anime Is This? - FAQ</title>
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
<li><a href="/faq" class="active">FAQ</a></li>
<li><a href="/terms">Terms</a></li>
</ul>
</div>
</nav>
<div class="container">
<div class="page-header"><h1>FAQ</h1></div>
<h3>Why I can't find the search result?</h3>
<p>
Possible reasons:<br>
1. Your image is not an original anime screenshot.<br>
2. The anime has not been analyzed yet. <br>
3. Your image is flipped. <br>
4. Your image is of bad quality. <br>
Regarding 1. You may try to use <a href="https://saucenao.com/">SauceNAO</a> and <a href="https://iqdb.org/">iqdb.org</a> which is best for searching anime artwork.<br>
Regarding 2. New animes currently airing would be analyzed around 24 hours after TV broadcast. Long-running animes / cartoons are excluded at this stage. See "What animes are indexed" at the bottom of this page.<br>
As for 3. If you image comes from AMV / Anime Compilations, it's likely its flipped horizontally. Use the flip button to search again.<br>
As for 4. The image search algorithm is designed for almost-exact match, not similar match. It analyze the color layout of the image. So, when your image is not a full un-cropped original 16:9 screenshot (i.e. cropped image), the search would likely fail. <br>
Color is an important factor for the correct search, if heavy tints and filters are applied to the screenshot (i.e. grayscale, contrast, saturate, brightness, sepia), too much information are lost. In this case the search would also fail. The Edge Histogram can solve this issue by ignoring colors and only search edges. But I am running out of computing resource to support another image descriptor.<br>
Image transform is also an important factor. If the image is not scaled without maintaining original aspect ratios (i.e. elongated, flipped, rotated), the search would also fail.<br>
Text occupied too much of the image. Large texts on the image would interfere the original image. The system is not smart enough to ignore the text.<br>
If you image has too little distinguish features (e.g. dark images or images with large plain blocks of plain colors), the search would also fail.<br>
Searching with a real photo (of an anime) definitely won't work.<br>
</p>
<h3>Examples of bad screenshots</h3>
<br>
<h4>Extra border added</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/border-bad.jpg" style="max-width:320px;max-height:180px"><br>Bad Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/border-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<p style="clear:both">In case your screenshot has extra borders, please trim off the extra borders before you search.</p>
<br>
<h4>Cropped Image</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/cropped-bad.jpg" style="max-width:320px;max-height:180px"><br>Bad Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/cropped-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<p style="clear:both">Cropping the image would result a huge loss of information content. </p>
<br>
<h4>Flipped image</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/flipped-bad.jpg" style="max-width:320px;max-height:180px"><br>Bad Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/flipped-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<p style="clear:both">This screenshot from <a href="https://www.youtube.com/watch?v=TUoWYoTWcnA&feature=youtu.be&t=2m59s">AMV - Animegraphy 2015</a> flipped the original scene in the anime. Try to search with the flip button if you guess the image has been flipped.</p>
<br>
<h4>Tinted images</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/tinted-bad.jpg" style="max-width:320px;max-height:180px"><br>Not a good Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/tinted-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<p style="clear:both">Tinted images are hard to search. Because the applied filter effects heavily distorted the information in the original screenshot. The color layout image descriptor can no longer find such images.</p>
<br>
<h4>Image covered by big text</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/bigtext-bad.jpg" style="max-width:320px;max-height:180px"><br>Not so good Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/bigtext-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<p style="clear:both">If you are lucky, you can still get correct search result.<br>(P.S. spot the difference between TV and Bluray?)</p>
<br>
<h4>Old Japanese Anime</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/old-bad.jpg" style="max-width:320px;max-height:180px"><br>Sample Screenshot</div>
<p style="clear:both">Anime of this age are not indexed.</p>
<br>
<h4>Not from Anime Screenshot</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/notanime-bad.jpg" style="max-width:320px;max-height:180px"><br>Sample Screenshot</div>
<p style="clear:both">You should try <a href="https://saucenao.com/">SauceNAO</a> and <a href="https://iqdb.org/">https://iqdb.org/</a> to search anime / doujin artwork.</p>
<br>
<h4>Not Japanese Anime</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/nonjapanese-bad.jpg" style="max-width:320px;max-height:180px"><br>Sample Screenshot</div>
<p style="clear:both">Tom and Jerry is obviously not a Japanese Anime.</p>
<br>
<h4>Dark image</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/dark-bad.jpg" style="max-width:320px;max-height:180px"><br>Sample Screenshot</div>
<p style="clear:both">Dark images are hard to distinguish using the colorlayout descriptor.</p>
<br>
<h4>Low resolution image</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/lowres-bad.jpg" style="max-width:320px;max-height:180px"><br>Sample Screenshot</div>
<p style="clear:both">Your image should be at least 320x180px to search effectively.</p>
<br>
<h3>Examples of acceptable screenshots</h3>
<h4>Slightly distorted size</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/distorted-bad.jpg" style="max-width:320px;max-height:180px"><br>Acceptable Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/distorted-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<p style="clear:both"></p>
<br>
<h4>Reasonably sized subtitles</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/subtitles-bad.jpg" style="max-width:320px;max-height:180px"><br>Acceptable Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/subtitles-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<p style="clear:both"></p>
<br>
<h4>A frame of GIF</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/gif-bad.jpg" style="max-width:320px;max-height:180px"><br>Acceptable Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/gif-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<p style="clear:both">If the color distortion is accetable, GIF is also OK. </p>
<br>
<h4>Drawings of the anime scene</h4>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/draw-bad.jpg" style="max-width:320px;max-height:180px"><br>Acceptable Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/draw-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/draw2-bad.jpg" style="max-width:320px;max-height:180px"><br>Acceptable Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/draw2-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/draw3-bad.jpg" style="max-width:320px;max-height:180px"><br>Acceptable Screenshot</div>
<div style="float:left;text-align:center;border:1px #666 solid;width:320px"><img src="/img/draw3-good.jpg" style="float:left;max-width:320px;max-height:180px"><br>Original Screenshot</div>
<p style="clear:both">The search image does not has to be taken from anime screencap directly. You can use drawings of some scenes as long as it is similar to the original one.</p>
<br>
<h3>How does it work?</h3>
<p>It uses the MPEG 7 Color Layout Descriptor for comparing images. Wikipedia has a good illustration about it. This would also explain why you can't find your image. <a href="https://en.wikipedia.org/wiki/Color_layout_descriptor">https://en.wikipedia.org/wiki/Color_layout_descriptor</a></p>
<h3>How to make the search for a more accurate result?</h3>
<p>Crop your screenshot to 16:9 or 4:3 before searching. Remove any extra borders in screencap (if any). By default, it crops the image to 16:9, if you upload a 16:10 screenshot, it should be cropped automatically. If the position is incorrect, you can drag the image and adjust the crop position.</p>
<h3>What animes are being indexed?</h3>
<p>Most japan anime since 2000 are indexed, plus some popular anime in 1990s, and little anime before 1990. A list of anime are incomplete in index at this stage, including Aikatsu!, Pretty Rhythm, PriPara, Precure, Jewelpet, Yu-Gi-Oh!, Dragon Ball, Crayon Shin-chan, Doraemon, Pokemon, Detective Conan, Chibi Maruko-chan and Hentai-anime.</p>
<h3>Why I can't preview the search result?</h3>
<p>There is no preview for mobile devices. Some animes are being removed or relocated, so some of the previews are offline now. The preview uses a considerable amount of network bandwidth, it would takes time to load if you have a slow connection.</p>
<h3>Why the anime are chinese-subbed?</h3>
<p>I am still collecting raw animes, and it would take a number of powerful servers several months to complete. It will switch to the new dataset once it is ready. The current dataset uses chinese-subbed anime because the current index is provided by some Asian users.</p>
<h3>How can I watch the entire anime?</h3>
<p>
This website is not intended for watching anime.<br>
If you wish to watch the anime, you may check which TV channel is broadcasting the anime in you country. For those which has finished airing, consider buying or renting the original Blu-ray/DVDs.
</p>
<h3>How can I share the search result?</h3>
<p>For now, you have to upload the image somewhere else, then search by image URL, and share the result URL. If you have any feedback or suggestions, feel free to contact <a href="mailto:help@whatanime.ga">help@whatanime.ga</a>.</p>
<h3>How to add whatanime.ga to Image Search Options</h3>
<p>
If you prefer to use whatanime.ga with <a href="https://chrome.google.com/webstore/detail/image-search-options/kljmejbpilkadikecejccebmccagifhl">Image Search Options</a>, go to settings and add this: <pre>https://whatanime.ga/?url=</pre>
You can also configure playback options via URL params:
<pre>https://whatanime.ga/?autoplay=0&loop&mute=1&url=</pre>
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
