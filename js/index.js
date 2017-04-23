$(document).ready(function () {
  var ua = navigator.userAgent
  if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile|mobile/i.test(ua)) {
    document.querySelector("#mute").checked = true;
    document.querySelector('#player').muted = true;
  }
  else{
    document.querySelector("#mute").checked = false;
    document.querySelector('#player').muted = false;
  }
  
  if (navigator.language.indexOf('ja') == 0) {
    $('#searchBtn').contents().last()[0].textContent = ' 検索'
    $('#safeBtn').contents().last()[0].textContent = ' セーフサーチ'
    $('#flipBtn').contents().last()[0].textContent = ' 反転'
    $('.btn-file').contents().first()[0].textContent = 'ファイル選択'
    $('#autoplay').parent().contents().last()[0].textContent = '自動再生'
    $('#loop').parent().contents().last()[0].textContent = 'ループ'
    $('#mute').parent().contents().last()[0].textContent = 'ミュート'
    $('#fitWidthRadio').parent().contents().last()[0].textContent = '横幅設定'
    $('#fitHeightRadio').parent().contents().last()[0].textContent = '縦幅設定'
    $('#imageURL').attr('placeholder', '画像 URL')
  }
  if (navigator.language.toLocaleLowerCase() == 'zh-cn') {
    $('#searchBtn').contents().last()[0].textContent = ' 搜寻'
    $('#safeBtn').contents().last()[0].textContent = ' 安全过滤'
    $('#flipBtn').contents().last()[0].textContent = ' 反转'
    $('.btn-file').contents().first()[0].textContent = '选取档案'
    $('#autoplay').parent().contents().last()[0].textContent = '自动播放'
    $('#loop').parent().contents().last()[0].textContent = '循环播放'
    $('#mute').parent().contents().last()[0].textContent = '静音'
    $('#fitWidthRadio').parent().contents().last()[0].textContent = '符合宽度'
    $('#fitHeightRadio').parent().contents().last()[0].textContent = '符合高度'
    $('#imageURL').attr('placeholder', '图片网址')
    $('#instruction').text(' / 拖放图片至上方 / Ctrl+V 贴上 / 输入图片网址')
  }
  else if (navigator.language.indexOf('zh') == 0) {
    $('#searchBtn').contents().last()[0].textContent = ' 搜尋'
    $('#safeBtn').contents().last()[0].textContent = ' 安全過濾'
    $('#flipBtn').contents().last()[0].textContent = ' 反轉'
    $('.btn-file').contents().first()[0].textContent = '選取檔案'
    $('#autoplay').parent().contents().last()[0].textContent = '自動播放'
    $('#loop').parent().contents().last()[0].textContent = '循環播放'
    $('#mute').parent().contents().last()[0].textContent = '靜音'
    $('#fitWidthRadio').parent().contents().last()[0].textContent = '乎合寬度'
    $('#fitHeightRadio').parent().contents().last()[0].textContent = '乎合高度'
    $('#imageURL').attr('placeholder', '圖片網址')
    $('#instruction').text(' / 拖放圖片至上方 / Ctrl+V 貼上 / 輸入圖片網址')
  }

  document.querySelector("#autoplay").checked = true;
  document.querySelector("#loop").checked = false;

  location.search.substr(1).split('&').forEach(function(param){
    let key = param.split('=')[0];
    let val = param.split('=')[1];
    if (key === "autoplay") {
      document.querySelector("#autoplay").checked = val !== "0" ? true : false;
    }
    if (key === "mute") {
      document.querySelector("#mute").checked = val !== "0" ? true : false;
    }
    if (key === "loop") {
      document.querySelector("#loop").checked = val !== "0" ? true : false;
    }
  });

  document.querySelector("#autoplay").onchange = updateURLParam;
  document.querySelector("#mute").onchange = updateURLParam;
  document.querySelector("#loop").onchange = updateURLParam;
})

var updateURLParam = function(){
  let urlString = '';
  let params = [];
  if(document.querySelector("#autoplay").checked !== true){
    params.push("autoplay=0");
  }
  if(document.querySelector("#mute").checked === true){
    params.push("mute");
  }
  if(document.querySelector("#loop").checked === true){
    params.push("loop");
  }
  urlString += params.sort().join('&');
  if(document.querySelector("#imageURL").value){
    if(params.length > 0){
      urlString += '&';
    }
    urlString += 'url='+encodeURI(document.querySelector("#imageURL").value.replace(/ /g,'%20'))
  }
  if(urlString.length > 0){
    history.replaceState(null,null,'/?'+urlString);
  }
  else{
    history.replaceState(null,null,'/');
  }
}

var formatTime = function (timeInSeconds) {
  var sec_num = parseInt(timeInSeconds, 10)
  var hours = Math.floor(sec_num / 3600)
  var minutes = Math.floor((sec_num - (hours * 3600)) / 60)
  var seconds = sec_num - (hours * 3600) - (minutes * 60)

  if (hours < 10) {hours = '0' + hours;}
  if (minutes < 10) {minutes = '0' + minutes;}
  if (seconds < 10) {seconds = '0' + seconds;}
  var timestring = hours + ':' + minutes + ':' + seconds

  return timestring
}

function zeroPad (n, width, z) {
  z = z || '0'
  n = n + ''
  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n
}

var player = document.querySelector('#player')
var preview = document.querySelector('#preview')
var autoSearch = document.querySelector('#autoSearch')
var originalImage = document.querySelector('#originalImage')
var fitWidth = true
var firstPlay = true

var safeSearch = false
var imgDataURL
var searchRequest
var search = function (trial, prev_result) {
  if (!trial) trial = 4;
  if (searchRequest && searchRequest.readyState != 4) {
    searchRequest.abort()
  }
  preview.classList.remove('movable')
  document.querySelector('#loading').classList.remove('hidden')
  document.querySelector('#searchBtn span').classList.remove('glyphicon-search')
  document.querySelector('#searchBtn span').classList.add('glyphicon-refresh')
  document.querySelector('#searchBtn span').classList.add('spinning')
  if (document.querySelector('#search2Btn span')) {
    document.querySelector('#search2Btn span').classList.remove('glyphicon-search')
    document.querySelector('#search2Btn span').classList.add('glyphicon-refresh')
    document.querySelector('#search2Btn span').classList.add('spinning')
  }
  resetInfo()
  animeInfo = ''
  document.querySelector('#player').pause()
  preview.removeEventListener('click', playPause)
  if (!trial) document.querySelector('#results').innerHTML = '<div id="status">Submitting image for searching...</div>'
  document.querySelector('#searchBtn').disabled = true
  if (document.querySelector('#search2Btn span')) document.querySelector('#search2Btn').disabled = true
  document.querySelector('#flipBtn').disabled = true
  document.querySelector('#imageURL').disabled = true
  
  searchRequest = $.post('/search',
    {'data': imgDataURL, 'filter': document.querySelector('#seasonSelector').value, 'trial': trial}, function (data, textStatus) {
      document.querySelector('#loading').classList.add('hidden')
      document.querySelector('#searchBtn').disabled = false
      if (document.querySelector('#search2Btn span')) document.querySelector('#search2Btn').disabled = false
      document.querySelector('#flipBtn').disabled = false
      document.querySelector('#imageURL').disabled = false
      document.querySelector('#results').innerHTML = ''
      if (prev_result){
        data.RawDocsCount += prev_result.RawDocsCount
        data.RawDocsSearchTime += prev_result.RawDocsSearchTime
        data.ReRankSearchTime += prev_result.ReRankSearchTime
      }
      
      ga('send', 'event', 'search', 'RawDocsCount', data.RawDocsCount)
      ga('send', 'event', 'search', 'RawDocsSearchTime', data.RawDocsSearchTime)
      ga('send', 'event', 'search', 'ReRankSearchTime', data.ReRankSearchTime)
      ga('send', 'event', 'search', 'Trial', data.trial, data.trial)
      document.querySelector('#results').innerHTML += '<div id="status">' + data.RawDocsCount + ' images searched in ' + ((data.RawDocsSearchTime + data.ReRankSearchTime) / 1000).toFixed(2) + ' seconds</div>'
      if (prev_result) data.docs = data.docs.concat(prev_result.docs);
      data.docs.sort(function(a,b){
        return a.diff - b.diff;
      });
      if (data.docs.length > 0) {
        $.each(data.docs, function (key, entry) {
          if (safeSearch == false || entry.season != 'Sukebei') {
            var similarity = (100 - parseFloat(entry.diff)).toFixed(1)
            var result = document.createElement('li')
            result.setAttribute('class', 'result')
            result.setAttribute('data-season', entry.season)
            result.setAttribute('data-anime', entry.anime)
            result.setAttribute('data-title', entry.title)
            result.setAttribute('data-title-english', entry.title_english)
            result.setAttribute('data-title-romaji', entry.title_romaji)
            result.setAttribute('data-file', entry.file)
            result.setAttribute('data-episode', entry.episode)
            result.setAttribute('data-start', entry.start)
            result.setAttribute('data-end', entry.end)
            result.setAttribute('data-token', entry.token)
            result.setAttribute('data-tokenthumb', entry.tokenthumb)
            result.setAttribute('data-expires', entry.expires)
            result.setAttribute('data-from', entry.from)
            result.setAttribute('data-to', entry.to)
            result.setAttribute('data-t', entry.t)
            
            var thumbnailLink = '/thumbnail.php?season=' + encodeURIComponent(entry.season) + '&anime=' + encodeURIComponent(entry.anime) + '&file=' + encodeURIComponent(entry.file) + '&t=' + (entry.t) + '&token=' + entry.tokenthumb
            var opacity = (Math.pow(((100 - parseFloat(entry.diff)) / 100), 4) + 0.2).toFixed(3)
            result.style.opacity = opacity > 1 ? 1 : opacity
            var title_display = entry.title_romaji
            if (navigator.language.indexOf('ja') == 0) title_display = entry.title || entry.anime
            if (navigator.language.indexOf('zh') == 0) title_display = entry.anime || entry.anime
            if (formatTime(entry.from) == formatTime(entry.to))
              result.innerHTML = '<a href="#"><span class="title">' + title_display + '</span><br><span class="ep">EP#' + zeroPad(entry.episode, 2) + '</span> <span class="time">' + formatTime(entry.from) + '</span> <span class="similarity">~' + similarity + '%</span><br><span class="file">' + entry.file + '</span><img src="' + thumbnailLink + '"></a>'
            else
              result.innerHTML = '<a href="#"><span class="title">' + title_display + '</span><br><span class="ep">EP#' + zeroPad(entry.episode, 2) + '</span> <span class="time">' + formatTime(entry.from) + '-' + formatTime(entry.to) + '</span> <span class="similarity">~' + similarity + '%</span><br><span class="file">' + entry.file + '</span><img src="' + thumbnailLink + '"></a>'
            document.querySelector('#results').appendChild(result)
          }
        })
        var topResult = '/' + data.docs[0].season + '/' + data.docs[0].anime + '/' + data.docs[0].file + '?start=' + data.docs[0].start + '&end=' + data.docs[0].end + '&t=' + data.docs[0].t
        ga('send', 'event', 'search', 'topResult', topResult, data.docs[0].diff)
        $('.result').click(playfile)
        firstPlay = true

        if (parseFloat(data.docs[0].diff) > 13) {
          if (trial < 6) search(trial + 1, data);
          else {
            if (trial < 12){
              $('#results').prepend('<div style="text-align:center"><button id="search2Btn" type="button" class="btn btn-default btn-sm btn-primary"><span class="glyphicon glyphicon-search"></span> Keep Searching</button></div>')
              $('#search2Btn').click(function(){search(trial + 1, data)})
            }
          }
        } else {
          if (trial < 12){
            $('#results').prepend('<div style="text-align:center"><button id="search2Btn" type="button" class="btn btn-default btn-sm btn-primary"><span class="glyphicon glyphicon-search"></span> Keep Searching</button></div>')
            $('#search2Btn').click(function(){search(trial + 1, data)})
          }
          if (safeSearch == false) {
            $('.result')[0].click()
          }
        }
        
      } else {
        document.querySelector('#results').innerHTML = '<div id="status">No result</div>'
      }
    }, 'json').fail(function (e) {
    if (e.status == 429) {
      ga('send', 'event', 'search', 'recaptcha', 'challenge')
      //document.querySelector('#results').innerHTML = ''
      grecaptcha.reset()
      $('.g-recaptcha').removeClass('hidden')
    } else {
      document.querySelector('#results').innerHTML = '<div id="status">Connection to Search Server Failed</div>'
    }
    document.querySelector('#searchBtn').disabled = false
    if (document.querySelector('#search2Btn span')) document.querySelector('#search2Btn').disabled = false
    document.querySelector('#flipBtn').disabled = false
    document.querySelector('#imageURL').disabled = false
  }).complete(function (e) {
    document.querySelector('#searchBtn span').classList.remove('glyphicon-refresh')
    document.querySelector('#searchBtn span').classList.remove('spinning')
    document.querySelector('#searchBtn span').classList.add('glyphicon-search')
    if (document.querySelector('#search2Btn span')) {
      document.querySelector('#search2Btn span').classList.remove('glyphicon-refresh')
      document.querySelector('#search2Btn span').classList.remove('spinning')
      document.querySelector('#search2Btn span').classList.add('glyphicon-search')
    }
  })
}
var recaptcha_success = function () {
  ga('send', 'event', 'search', 'recaptcha', 'pass')
  $.post('/search',
    {'g-recaptcha-response': document.querySelector('#g-recaptcha-response').value}, function (data, textStatus) {
      $('.g-recaptcha').addClass('hidden')
      document.querySelector('#loading').classList.add('hidden')
    }).fail(function (e) {
    document.querySelector('#results').innerHTML = '<div id="status">Connection to Search Server Failed</div>'
  })
}
$('#searchBtn').click(function () {search()})
$('#flipBtn').click(function () {drawPreview(searchImage);resetAll();drawPreview(searchImage);flip();drawPreview(searchImage);})
$('#safeBtn').click(function () {safeToggle()})
$('form').submit(function () {event.preventDefault();})

var fetchImageDelay
var fetchImageMsgDelay
$('#imageURL').bind('input', function () {
  clearTimeout(fetchImageDelay)
  clearTimeout(fetchImageMsgDelay)
  document.querySelector('#messageText').classList.remove('error')
  document.querySelector('#messageText').classList.remove('success')
  document.querySelector('#messageText').innerText = ''
  if (document.querySelector('#imageURL').value.length) {
    fetchImageDelay = setTimeout(function () {
      fetchImageMsgDelay = setTimeout(function () {document.querySelector('#messageText').innerHTML = '<span class="glyphicon glyphicon-repeat spinning"></span>';}, 500)
      originalImage.src = 'https://image.whatanime.ga/imgproxy?url=' + document.querySelector('#imageURL').value.replace(/ /g, '%20')
      history.replaceState(null, null, '/?url=' + encodeURI(document.querySelector('#imageURL').value.replace(/ /g, '%20')))
    }, 500)
  } else {
    history.replaceState(null, null, '/')
  }
})

var safeToggle = function () {
  if ($('#safeBtn').children('.glyphicon').hasClass('glyphicon-unchecked')) {
    safeSearch = true
    $('#safeBtn').children('.glyphicon').removeClass('glyphicon-unchecked')
    $('#safeBtn').children('.glyphicon').addClass('glyphicon-check')
  } else {
    safeSearch = false
    $('#safeBtn').children('.glyphicon').addClass('glyphicon-unchecked')
    $('#safeBtn').children('.glyphicon').removeClass('glyphicon-check')
  }
}

$('#mute').change(function () {
  if (document.querySelector('#mute').checked)
    document.querySelector('#player').muted = true;
  else
    document.querySelector('#player').muted = false;
})

$('input[type=radio][name=fitRadio]').change(function () {
  resetAll()
  drawPreview(searchImage)
  if (document.querySelector('#fitWidthRadio').checked)
    fitWidth = true
  else
    fitWidth = false
  if (searchImage.width > 0 && searchImage.height > 0) {
    changeAspectRatio()
    drawPreview(searchImage)
  }
  drawPreview(searchImage)
})

var sampleScreen = function () {
  preview_heartbeat = window.requestAnimationFrame(sampleScreen)
  drawVideoPreview(player)
}

preview.addEventListener('contextmenu', function (e) {
  e.preventDefault()
})

var previewing
var preview_heartbeat
var time
var animeInfo
var playfile = function () {
  $('.result').removeClass('active')
  $(this).addClass('active')
  document.querySelector('#player').pause()
  window.cancelAnimationFrame(preview_heartbeat)
  if (!firstPlay)
    preview.getContext('2d').fillRect(0, 0, 640, 360)
  firstPlay = false
  preview_heartbeat = window.requestAnimationFrame(sampleScreen)
  clearTimeout(previewing)
  var season = $(this).attr('data-season')
  var anime = $(this).attr('data-anime')
  var title = $(this).attr('data-title')
  var episode = $(this).attr('data-episode')
  var file = $(this).attr('data-file')
  var start = $(this).attr('data-start')
  var end = $(this).attr('data-end')
  var token = $(this).attr('data-token')
  var expires = $(this).attr('data-expires')
  var tfrom = $(this).attr('data-from')
  var tto = $(this).attr('data-to')
  var t = $(this).attr('data-t')
  var src = '/' + season + '/' + encodeURIComponent(anime) + '/' + encodeURIComponent(file) + '?start=' + start + '&end=' + end + '&token=' + token
  document.querySelector('#loading').classList.remove('hidden')
  document.querySelector('#player').src = src
  if (document.querySelector('#autoplay').checked) {
    time = parseFloat(tfrom) - parseFloat(start)
  } else {
    time = parseFloat(t) - parseFloat(start)
  }
  if (time < 0) time = 0
  ga('send', 'event', 'playfile', 'src', '/' + season + '/' + (anime) + '/' + (file), t)

  if (animeInfo != season + anime) {
    animeInfo = season + anime
    getInfo(season, anime)
  }
}

var playPause = function () {
  if (player.currentTime < time)
    document.querySelector('#player').currentTime = time
  if (player.paused)
    player.play()
  else
    player.pause()
}
var canPlayThrough = function () {
  document.querySelector('#loading').classList.add('hidden')
}
var loadedmetadata = function (event) {
  preview.addEventListener('click', playPause)
  player.currentTime = time
  if (document.querySelector('#autoplay').checked) {
    player.oncanplaythrough = canPlayThrough
    player.play()
  }
}
document.querySelector('#player').addEventListener('loadedmetadata', loadedmetadata , false)
var ended = function (event) {
  if (document.querySelector('#loop').checked) {
    document.querySelector('#player').currentTime = time - 0.3
    player.play()
  }
  else
    document.querySelector('#player').currentTime = time
}
document.querySelector('#player').addEventListener('ended', ended , false)

var searchImage = document.createElement('canvas')
searchImage.width = 0
searchImage.height = 0

var cropOffset = 0

var changeAspectRatio = function () {
  preview.removeEventListener('mousedown', startCropArea)
  preview.removeEventListener('mouseup', stopCropArea)
  preview.removeEventListener('mousemove', moveCropArea)
  preview.removeEventListener('mouseout', stopCropArea)
  preview.classList.remove('movable')

  var imageAspectRatio = originalImage.width / originalImage.height

  if (fitWidth) {
    searchImage.width = 640
    searchImage.height = 640 / imageAspectRatio
    cropOffset = (searchImage.height - 360) / 2
    if (imageAspectRatio < 1) cropOffset = 0
    if (640 / imageAspectRatio > 360) {
      preview.addEventListener('mousedown', startCropArea)
      preview.addEventListener('mouseup', stopCropArea)
      preview.addEventListener('mousemove', moveCropArea)
      preview.addEventListener('mouseout', stopCropArea)
      preview.classList.add('movable')
    }
    drawPreview(searchImage)
  } else {
    searchImage.width = 360 * imageAspectRatio
    searchImage.height = 360
    cropOffset = (originalImage.width - searchImage.width) / 2 / (originalImage.height / 360)
    drawPreview(searchImage)
  }
  crop()
  drawPreview(searchImage)
}

var resetAll = function () {
  preview.getContext('2d').fillRect(0, 0, 640, 360)
  resetInfo()
  document.querySelector('#player').pause()
  preview.removeEventListener('click', playPause)
  window.cancelAnimationFrame(preview_heartbeat)
  changeAspectRatio()
  drawPreview(searchImage)
}

originalImage.onload = function () {
  clearTimeout(fetchImageMsgDelay)
  resetAll()
  changeAspectRatio()
  drawPreview(searchImage)
  document.querySelector('#searchBtn').disabled = false
  document.querySelector('#flipBtn').disabled = false
  document.querySelector('#messageText').classList.add('success')
  document.querySelector('#messageText').innerHTML = ''
  document.querySelector('#results').innerHTML = '<div id="status">Press Search button to begin searching.</div>'
  if(document.querySelector("#autoSearch").checked){
    document.querySelector("#autoSearch").checked = false;
    search()
  }
}
originalImage.onerror = function () {
  document.querySelector('#messageText').classList.add('error')
  document.querySelector('#messageText').innerText = ''
}

var initMove = 0
var lastMove = 0
var crop = function () {
  var sx, sy, swidth, sheight
  var imageAspectRatio = originalImage.width / originalImage.height
  if (fitWidth) {
    searchImage.width = 640
    swidth = originalImage.width
    if (searchImage.height >= 360) {
      searchImage.height = 360
      sheight = originalImage.width / 16 * 9
    } else {
      sheight = originalImage.width / imageAspectRatio
    }
    sx = 0
    sy = cropOffset * (originalImage.width / 640)
    if (sy > originalImage.height - sheight) sy = originalImage.height - sheight
    if (sy < 0) sy = 0
    lastMove = sy / (originalImage.width / 640)
    drawPreview(searchImage)
  } else {
    searchImage.height = 360
    sheight = originalImage.height
    if (searchImage.width >= 640) {
      searchImage.width = 640
      swidth = originalImage.height * 16 / 9
    } else {
      swidth = originalImage.height * imageAspectRatio
    }
    sx = cropOffset
    sy = 0
    if (sx > originalImage.width - swidth) sx = originalImage.width - swidth
    if (sx < 0) sx = 0
    lastMove = sx / (originalImage.height / 360)
    drawPreview(searchImage)
  }

  searchImage.getContext('2d').drawImage(originalImage, sx, sy, swidth, sheight, 0, 0, searchImage.width, searchImage.height)

  searchImage.getContext('2d').save()
  searchImage.getContext('2d').translate(searchImage.width, 0)
  if ($('#flipBtn').children('.glyphicon').hasClass('glyphicon-unchecked'))
    searchImage.getContext('2d').scale(1, 1)
  else
    searchImage.getContext('2d').scale(-1, 1)
  searchImage.getContext('2d').drawImage(searchImage, 0, 0)
  searchImage.getContext('2d').restore()

  drawPreview(searchImage)
  imgDataURL = searchImage.toDataURL('image/jpeg', 0.8)
}

var cropping = false
var moveCropArea = function (e) {
  if (cropping) {
    if (e.buttons == 1) {
      if (initMove == 0) {
        initMove = e.offsetY
        initMove += lastMove
      }
      cropOffset = initMove - e.offsetY
      crop()
    }
  } else {
    initMove = 0
  }
}
var stopCropArea = function (e) {
  cropping = false
}
var startCropArea = function (e) {
  cropping = true
}

var flip = function () {
  drawPreview(searchImage)
  if ($('#flipBtn').children('.glyphicon').hasClass('glyphicon-unchecked')) {
    $('#flipBtn').children('.glyphicon').removeClass('glyphicon-unchecked')
    $('#flipBtn').children('.glyphicon').addClass('glyphicon-check')
  } else {
    $('#flipBtn').children('.glyphicon').addClass('glyphicon-unchecked')
    $('#flipBtn').children('.glyphicon').removeClass('glyphicon-check')
  }
  crop()
  drawPreview(searchImage)
  imgDataURL = searchImage.toDataURL('image/jpeg', 0.9)
}

var drawPreview = function (source) {
  var x,y,w,h,aspectRatio
  aspectRatio = source.width / source.height

  if (source.width < 640 || source.height < 360)
    preview.getContext('2d').fillRect(0, 0, 640, 360)

  if (fitWidth) {
    w = 640
    h = w / aspectRatio
    x = 0
    y = (360 - h) / 2
  } else {
    h = 360
    w = h * aspectRatio
    x = (640 - w) / 2
    y = 0
  }
  var ctx = preview.getContext('2d')
  ctx.save()
  ctx.scale(-1, 1)
  ctx.drawImage(source, x, y, w, h)
}

var drawVideoPreview = function (source) {
  var x,y,w,h,aspectRatio
  if (source.height == 0 && source.width == 0)
    aspectRatio = source.videoWidth / source.videoHeight
  else
    aspectRatio = source.width / source.height
  if (aspectRatio < (16 / 9) - 0.05) {
    h = 360
    w = h * aspectRatio
    x = (640 - w) / 2
    y = 0
  } else {
    w = 640
    h = w / aspectRatio
    x = 0
    y = (360 - h) / 2
  }
  var ctx = preview.getContext('2d')
  ctx.save()
  ctx.scale(-1, 1)
  ctx.drawImage(source, x, y, w, h)
}

function handleFileSelect (evt) {
  evt.stopPropagation()
  evt.preventDefault()

  var file
  if (typeof (evt.dataTransfer) !== 'undefined')
    file = evt.dataTransfer.files[0]
  else
    file = evt.target.files[0]

  if (file) {
    document.querySelector('#results').innerHTML = '<div id="status">Reading File...</div>'
    if (file.type.match('image.*')) {
      var reader = new FileReader()
      reader.onload = (function (theFile) {
        return function (e) {
          originalImage.src = e.target.result
        }
      })(file)
      reader.readAsDataURL(file)
    } else {
      document.querySelector('#results').innerHTML = '<div id="status">Error: File is not an image</div>'
      return false
    }
  }
}

function handleDragOver (evt) {
  evt.stopPropagation()
  evt.preventDefault()
  evt.dataTransfer.dropEffect = 'copy'
}

var dropZone = document.querySelector('#preview')
dropZone.addEventListener('dragover', handleDragOver, false)
dropZone.addEventListener('drop', handleFileSelect, false)

document.querySelector('#file').addEventListener('change', handleFileSelect, false)

var CLIPBOARD = new CLIPBOARD_CLASS('preview')

// cliboard pasting class
function CLIPBOARD_CLASS (canvas_id) {
  var _self = this
  var canvas = document.getElementById(canvas_id)
  var ctx = document.getElementById(canvas_id).getContext('2d')
  var ctrl_pressed = false
  var reading_dom = false
  var text_top = 15
  var pasteCatcher
  var paste_mode

  // handlers
  // document.addEventListener('keydown', function(e){ _self.on_keyboard_action(e); }, false) //firefox fix
  // document.addEventListener('keyup', function(e){ _self.on_keyboardup_action(e); }, false) //firefox fix
  document.addEventListener('paste', function (e) { _self.paste_auto(e); }, false) // official paste handler

  // constructor - prepare
  this.init = function () {
    // if using auto
    if (window.Clipboard) return true

    pasteCatcher = document.createElement('div')
    pasteCatcher.setAttribute('id', 'paste_ff')
    pasteCatcher.setAttribute('contenteditable', '')
    pasteCatcher.style.cssText = 'opacity:0;position:fixed;top:0px;left:0px;'
    pasteCatcher.style.marginLeft = '-20px'
    pasteCatcher.style.width = '10px'
    document.body.appendChild(pasteCatcher)
    document.getElementById('paste_ff').addEventListener('DOMSubtreeModified', function () {
      reading_dom = false
      if (paste_mode == 'auto' || ctrl_pressed == false) return true
      // if paste handle failed - capture pasted object manually
      if (pasteCatcher.children.length == 1) {
        if (pasteCatcher.firstElementChild.src != undefined) {
          // image
          _self.paste_createImage(pasteCatcher.firstElementChild.src)
        } else {
          // html
          setTimeout(function () {
            if (reading_dom == true) return false
            _self.paste_createText(pasteCatcher.innerHTML, false)
            reading_dom = true
          }, 10)
        }
      }
      else if (pasteCatcher.children.length == 0) {
        // text
        setTimeout(function () {
          if (reading_dom == true) return false
          _self.paste_createText(pasteCatcher.innerHTML, false)
          reading_dom = true
        }, 10)
      }
      // register cleanup after some time.
      setTimeout(function () {
        pasteCatcher.innerHTML = ''
      }, 20)
    }, false)
  }()
  // default paste action
  this.paste_auto = function (e) {
    if (e.target != document.querySelector('#imageURL')) {
      paste_mode = ''
      pasteCatcher.innerHTML = ''
      var plain_text_used = false
      if (e.clipboardData) {
        var items = e.clipboardData.items
        if (items) {
          paste_mode = 'auto'
          // access data directly
          for (var i = 0; i < items.length; i++) {
            if (items[i].type.indexOf('image') !== -1) {
              // image
              var blob = items[i].getAsFile()
              var URLObj = window.URL || window.webkitURL
              var source = URLObj.createObjectURL(blob)
              this.paste_createImage(source)
            }
            else if (items[i].type.indexOf('text') !== -1) {
              // text or html
              // alert("Please directly copy the image, not the link of the image")
              /*
              if(plain_text_used == false)
                  this.paste_createText(e.clipboardData.getData('text/plain'))
              plain_text_used = true
              */
            }
          }
          e.preventDefault()
        } else {
          // wait for DOMSubtreeModified event
          // https://bugzilla.mozilla.org/show_bug.cgi?id=891247
        }
      }
    }
  }
  // on keyboard press
  this.on_keyboard_action = function (event) {
    k = event.keyCode
    // ctrl
    if (k == 17 || event.metaKey || event.ctrlKey) {
      if (ctrl_pressed == false)
        ctrl_pressed = true
    }
    // c
    if (k == 86) {
      if (ctrl_pressed == true && !window.Clipboard)
        pasteCatcher.focus()
    }
  }
  // on kaybord release
  this.on_keyboardup_action = function (event) {
    k = event.keyCode
    // ctrl
    if (k == 17 || event.metaKey || event.ctrlKey || event.key == 'Meta')
      ctrl_pressed = false
  }
  // draw image
  this.paste_createImage = function (source) {
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    var pastedImage = new Image()
    pastedImage.onload = function () {
      ctx.drawImage(pastedImage, 0, 0)
    }
    originalImage.src = source
  }
  // draw text
  this.paste_createText = function (text, parsed) {
    if (text == '') return false
    if (parsed == false) {
      text = text.replace(/<br\s*[\/]?>/gi, '\n')
      text = text.replace(/(<([^>]+)>)/g, '')
      text = text.replace(/&nbsp;/gi, ' ')
    }
    ctx.font = '13px Tahoma'
    var lines = text.split('\n')
    for (var i in lines) {
      ctx.fillText(lines[i], 10, text_top)
      text_top += 15
    }
    text_top += 15
  }
}

var getInfo = function (season, anime) {
  resetInfo()
  $.get('/info?season=' + encodeURIComponent(season) + '&anime=' + encodeURIComponent(anime), function (data, textStatus) {
    if (data.length > 0) {
      displayInfo(data[0])
      document.querySelector('#info').style.visibility = 'visible'
      document.querySelector('#info').style.opacity = 1
    }
  }, 'json')
}

var resetInfo = function () {
  document.querySelector('#info').innerHTML = ''
  document.querySelector('#info').style.visibility = 'hidden'
  document.querySelector('#info').style.opacity = 0
}

var displayInfo = function (src) {
  $('<h1>', {text: src.title_japanese, style: 'font-size:1.5em'}).appendTo('#info')
  $('<h2>', {text: src.title_romaji}).appendTo('#info')
  $('<h2>', {text: src.title_english}).appendTo('#info')
  $('<h2>', {text: src.title_chinese}).appendTo('#info')
  $('<div>', {style: 'clear:both; border-bottom:1px solid #666; margin-bottom:13px'}).appendTo('#info')

  if (src.image_url_lge) {
    var div = $('<div>', {id: 'poster'}).appendTo('#info')
    $('<a>', {href: '//anilist.co/anime/' + src.id, target: '_blank'}).appendTo('#poster')
    $('<img>', {src: src.image_url_lge.replace('http:', '')}).appendTo('#poster a')
    div.appendTo('#info')
  }

  var naturalText = ''
  if (src.duration) {
    if (src.total_episodes == 1)
      naturalText += src.duration + ' minutes'
  }

  if (src.total_episodes)
    if (src.type != 'Movie')
      naturalText += src.total_episodes + ' episode '
  if (src.type)
    naturalText += src.type
  naturalText += ' Anime'

  if (src.duration) {
    if (src.total_episodes > 1)
      naturalText += ' (' + src.duration + ' minutes each)'
  }

  if (src.start_date && src.end_date) {
    if (src.type == 'Movie') {
      if (src.start_date == src.end_date)
        naturalText += '. Released on ' + src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, '$1-$2-$3')
      else
        naturalText += '. Released during ' + src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, '$1-$2-$3') + ' to ' + src.end_date.replace(/(\d+)-(\d+)-(\d+)T.*/, '$1-$2-$3')
    } else {
      if (src.start_date == src.end_date)
        naturalText += '. Released on ' + src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, '$1-$2-$3')
      else
        naturalText += '. Airing from ' + src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, '$1-$2-$3') + ' to ' + src.end_date.replace(/(\d+)-(\d+)-(\d+)T.*/, '$1-$2-$3')
    }
  }
  else if (src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, '$1') != '1970') {
    if (src.type == 'TV' || src.type == 'TV Short') {
      naturalText += '. Airing since ' + src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, '$1-$2-$3')
    }
  }

  naturalText += '。'
  $('<div>', {id: 'naturalText', text: naturalText}).appendTo('#info')

  $('<table>', {id: 'table'}).appendTo('#info')
  var row = $('<tr>')
  $('<td>', {text: 'Score'}).appendTo(row)
  if (src.average_score > 0)
    $('<td>', {text: parseFloat(src.average_score).toFixed(1)}).appendTo(row)
  else
    $('<td>', {text: '-'}).appendTo(row)
  row.appendTo('#info #table')

  var row = $('<tr>')
  $('<td>', {text: 'Popularity'}).appendTo(row)
  $('<td>', {text: src.popularity}).appendTo(row)
  row.appendTo('#info #table')

  var row = $('<tr>')
  $('<td>', {text: 'Drop rate'}).appendTo(row)
  if (src.popularity > 0)
    $('<td>', {text: (src.list_stats.dropped / src.popularity * 100).toFixed(1) + '%'}).appendTo(row)
  else
    $('<td>', {text: '-'}).appendTo(row)
  row.appendTo('#info #table')

  if (src.genres.length > 0) {
    var row = $('<tr>')
    $('<td>', {text: 'Genre'}).appendTo(row)
    $.each(src.genres, function (key, entry) {
      src.genres[key] = entry
    })
    $('<td>', {text: src.genres.join(', ')}).appendTo(row)
    row.appendTo('#info #table')
  }

  if (src.studio.length > 0) {
    var row = $('<tr>')
    $('<td>', {text: 'Studio'}).appendTo(row)
    var td = $('<td>')
    $.each(src.studio, function (key, entry) {
      if (entry.studio_wiki)
        $('<a>', {href: entry.studio_wiki, target: '_blank', text: entry.studio_name}).appendTo(td)
      else
        $('<span>', {text: entry.studio_name}).appendTo(td)
      $('<br>').appendTo(td)
    })
    td.appendTo(row)
    row.appendTo('#info #table')
  }

  if (src.synonyms.length > 0) {
    var row = $('<tr>')
    $('<td>', {text: 'Alias'}).appendTo(row)
    $('<td>', {html: src.synonyms.join('<br>')}).appendTo(row)
    row.appendTo('#info #table')
  }
  if (src.synonyms_chinese.length > 0) {
    var row = $('<tr>')
    $('<td>', {text: 'Alias'}).appendTo(row)
    $('<td>', {html: src.synonyms_chinese.join('<br>')}).appendTo(row)
    row.appendTo('#info #table')
  }

  if (src.classification) {
    var row = $('<tr>')
    $('<td>', {text: 'Rating'}).appendTo(row)
    $('<td>', {text: src.classification}).appendTo(row)
    row.appendTo('#info #table')
  }

  if (src.external_links.length > 0) {
    var row = $('<tr>')
    $('<td>', {text: 'External Links'}).appendTo(row)
    var td = $('<td>')
    $.each(src.external_links, function (key, entry) {
      $('<a>', {href: entry.url, target: '_blank', text: entry.site + ' '}).appendTo(td)
      $('<br>').appendTo(td)
    })
    td.appendTo(row)
    row.appendTo('#info #table')
  }

  if (src.staff.length > 0) {
    $('<br>', {style: 'clear:both'}).appendTo('#info')
    $('<h3>', {text: 'Staff'}).appendTo('#info')
    $('<div>', {style: 'clear:both; border-bottom:1px solid #666; margin-bottom:3px'}).appendTo('#info')

    var staffTable = $('<table>', {id: 'staff'})
    $.each(src.staff, function (key, entry) {
      var row = $('<tr>')
      var name = entry.name_first;
      if (entry.name_last) name += " " + entry.name_last;
      if (entry.names_first_japanese && entry.names_last_japanese)
        name = entry.names_first_japanese + entry.names_last_japanese;
      if (entry.role)
        $('<td>', {text: entry.role}).appendTo(row)
      else
        $('<td>', {text: entry.role.replace('Theme Song Performance', 'Theme Song Performance')}).appendTo(row)

      var nameTD = $('<td>')
      $('<a>', {class: 'staff_' + entry.id, href: '//anilist.co/staff/' + entry.id, target: '_blank', text: name}).appendTo(nameTD)
      nameTD.appendTo(row)
      row.appendTo(staffTable)
    })
    staffTable.appendTo('#info')
  }

  if (src.youtube_id) {
    $('<br>', {style: 'clear:both'}).appendTo('#info')
    $('<h3>', {text: 'Youtube PV'}).appendTo('#info')
    $('<div>', {style: 'clear:both; border-bottom:1px solid #666; margin-bottom:3px'}).appendTo('#info')
    $('<div>', {html: '<iframe id="youtube" width="100%" height="360" src="https://www.youtube.com/embed/' + src.youtube_id + '" frameborder="0" allowfullscreen></iframe>'}).appendTo('#info')
  }
  $('<br>', {style: 'clear:both'}).appendTo('#info')
  $('<h3>', {text: 'Synopses'}).appendTo('#info')
  $('<div>', {style: 'clear:both; border-bottom:1px solid #666; margin-bottom:3px'}).appendTo('#info')
  $('<div>', {html: src.description, style: 'text-align:justify'}).appendTo('#info')

  if (src.characters.length > 0) {
    $('<br>', {style: 'clear:both'}).appendTo('#info')
    $('<h3>', {text: 'Characters'}).appendTo('#info')
    $('<div>', {style: 'clear:both; border-bottom:1px solid #666; margin-bottom:3px'}).appendTo('#info')
    var characterDIV = $('<div>', {style: 'display:inline-block'})
    $.each(src.characters, function (key, entry) {
      var charDIV = $('<div>', {class: 'character'})
      var charImgDiv = $('<div>')
      if (entry.image_url_lge == '//anilist.co')
        entry.image_url_lge = '//anilist.co/img/dir/anime/reg/noimg.jpg'
      if (entry.image_url_med == '//anilist.co')
        entry.image_url_med = '//anilist.co/img/dir/anime/med/noimg.jpg'
      var charIMG = $('<a>', {href: entry.image_url_lge.replace('http:', ''), target: '_blank'}).appendTo(charImgDiv)
      $('<div>', {style: 'background-image:url(' + entry.image_url_med.replace('http:', '') + ')'}).appendTo(charIMG)
      charImgDiv.appendTo(charDIV)
      var charNameDiv = $('<div>')
      var charName = $('<div>');
      var char_name = entry.name_first;
      if (entry.name_last) char_name += ' ' + entry.name_last;
      if (entry.name_japanese) char_name = entry.name_japanese;
      var charName = $('<div>')
      $('<a>', {class: 'character_' + entry.id, href: '//anilist.co/character/' + entry.id, target: '_blank', text: char_name}).appendTo(charName)
      if (entry.actor.length > 0) {
        $('<br>').appendTo(charName)
        var name = entry.actor[0].name_first;
        if (entry.actor[0].name_last) name += " " + entry.actor[0].name_last;
        if (entry.actor[0].names_first_japanese && entry.actor[0].names_last_japanese)
          name = entry.actor[0].names_first_japanese + entry.actor[0].names_last_japanese;
        charName.append(document.createTextNode('(CV: '))
        $('<a>', {class: 'staff_' + entry.actor[0].id, href: '//anilist.co/staff/' + entry.actor[0].id, target: '_blank', text: name}).appendTo(charName)
        charName.append(document.createTextNode(')'))
      }
      charName.appendTo(charNameDiv)
      charNameDiv.appendTo(charDIV)
      charDIV.appendTo(characterDIV)
    })
    characterDIV.appendTo('#info')
  }

  $('<div>', {style: 'clear:both; border-bottom:1px solid #666; margin-bottom:3px'}).appendTo('#info')
  $('<div>', {html: 'Information provided by <a href="https://anilist.co" target="_blank">anilist.co</a>', style: 'float:right;font-size:12px'}).appendTo('#info')
  $('<div>', {style: 'clear:both'}).appendTo('#info')
}
