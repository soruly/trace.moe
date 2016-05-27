<?php
ini_set("display_errors", 0);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://192.168.2.11:8983/solr/anime_cl/admin/luke?wt=json");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($curl);
$result = json_decode($res);
curl_close($curl);

$numDocs = $result->index->numDocs;
$numDocsMillion = floor($numDocs / 1000000);

?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="description" content="Search Anime by ScreenShot. Search over <?php echo $numDocsMillion; ?> millions of images to lookup what anime, which episode, which moment the screenshot is taken from.">
<meta name="keywords" content="Anime Reverse Search, Search by image, which Anime, 逆向動畫搜尋, Reverse Image Search for Anime, アニメのキャプ画像">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=995, initial-scale=1">
<title>WAIT: What Anime Is This? - Anime Reverse Search Engine</title>
<link rel="icon" type="image/png" href="/favicon.png">
<link rel="icon" type="image/png" href="/favicon128.png" sizes="128x128">
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/style.css" rel="stylesheet">
<link href="/index.css" rel="stylesheet">
<link rel="dns-prefetch" href="https://image.whatanime.ga/">
<script src="https://www.google.com/recaptcha/api.js" defer></script>
<script src="/analytics.js" defer></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<script>
(function async(u, c) {
  var d = document, t = 'script',
      o = d.createElement(t),
      s = d.getElementsByTagName(t)[0];
  o.src = '//' + u;
  if (c) { o.addEventListener('load', function (e) { c(null, e); }, false); }
  s.parentNode.insertBefore(o, s);
})('//vk.com/js/api/share.js?93',function(){
  if(typeof VK !== "undefined"){document.getElementById('vk_share_button').innerHTML = VK.Share.button({url: "https://whatanime.ga/"},{type: "round", text: "Share"});}
});
</script>
<!--<div class="alert alert-warning">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Scheduled Maintenance</strong> A server maintenance will be carried out on 22 Mar, 2016 01:30-09:30am (UTC)
</div>-->
<div class="row">
<div class="col-xs-8">
<div id="main">
<div class="noselect">
<canvas id="preview" width="640" height="360"></canvas>
<video id="player" style="display:none" volume="1" ></video>
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
<span id="mobilePreviewDisabledText" style="display:none;color:#4CAF50">Preview is disabled on mobile devices. Use it on a Desktop / Laptop.<br></span>
Get the <a href="https://chrome.google.com/webstore/detail/search-anime-by-screensho/gkamnldpllcbiidlfacaccdoadedncfp" target="_blank">Chrome Extension</a>.<br>
<br>
<div class="share">
<span class="facebook"><div class="fb-share-button" data-href="https://whatanime.ga/" data-layout="button_count"></div></span>
<span class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-text="Anime Reverse Search Engine">Tweet</a></span>
<span class="vk" id="vk_share_button"></span>
<span class="google"><g:plus action="share"></g:plus></span>
</div>

<br>
<!-- or <input type="text" placeholder="Or right click and paste image here" /> (Not URL, copy the image itself)-->
<!--<input type="range" id="volume" name="volume" min="0" max="1" step="0.01" value="0.3" /> Volume-->
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
<?php if(isset($_GET["url"])){
	if(isset($_GET["auto"])){
		echo '<script>auto = true;</script>';
	}
echo '<script>
document.querySelector("#messageText").classList.remove("error");
document.querySelector("#messageText").classList.remove("success");
document.querySelector("#messageText").innerHTML = \'<span class="glyphicon glyphicon-repeat spinning"></span>\';
originalImage.src = "https://image.whatanime.ga/imgproxy?url='.str_replace(' ','%20',rawurldecode($_GET["url"])).'";
document.querySelector("#imageURL").value = "'.str_replace(' ','%20',rawurldecode($_GET["url"])).'";
history.replaceState(null,null,\'/?url=\'+encodeURI(document.querySelector("#imageURL").value.replace(/ /g,\'%20\')));
</script>';

}
?>
</body>
</html>
