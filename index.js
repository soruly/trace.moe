$(document).ready(function () {
  var ua = navigator.userAgent
  if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile|mobile/i.test(ua)) {
    $('#mobilePreviewDisabledText').show()
  }
  else if (/Chrome/i.test(ua)) {
    // $('#ctrlV').show()
  } else {
    // $('a.desktop-other').show()
  }
  if (navigator.language == 'ja' || navigator.language == 'ja-jp') {
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
  if (navigator.language == 'zh-tw' || navigator.language == 'zh-hk') {
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
  if (navigator.language == 'zh-cn') {
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
})

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
var fitWidth = true
var firstPlay = true
var auto = false

var safeSearch = false
var imgDataURL
var searchRequest
var search = function () {
  if (searchRequest && searchRequest.readyState != 4) {
    searchRequest.abort()
  }
  preview.classList.remove('movable')
  preview.classList.add('blur')
  document.querySelector('#searchBtn span').classList.remove('glyphicon-search')
  document.querySelector('#searchBtn span').classList.add('glyphicon-refresh')
  document.querySelector('#searchBtn span').classList.add('spinning')
  resetInfo()
  animeInfo = ''
  document.querySelector('#player').pause()
  preview.removeEventListener('click', playPause)
  document.querySelector('#results').innerHTML = '<div id="status">Submitting image for searching...</div>'
  document.querySelector('#searchBtn').disabled = true
  document.querySelector('#flipBtn').disabled = true
  document.querySelector('#imageURL').disabled = true
  // ga('send', 'event', 'search', 'imgDataURLLength', imgDataURL.length, imgDataURL.length)
  searchRequest = $.post('/search',
    {'data': imgDataURL}, function (data, textStatus) {
      document.querySelector('#searchBtn').disabled = false
      document.querySelector('#flipBtn').disabled = false
      document.querySelector('#imageURL').disabled = false
      document.querySelector('#results').innerHTML = ''
      var rawDocsCountTotal = 0
      var rawDocsSearchTimeTotal = 0
      var reRankSearchTimeTotal = 0
      for (i in data.RawDocsCount)
        rawDocsCountTotal += data.RawDocsCount[i]
      for (i in data.RawDocsSearchTime)
        rawDocsSearchTimeTotal += data.RawDocsSearchTime[i]
      for (i in data.ReRankSearchTime)
        reRankSearchTimeTotal += data.ReRankSearchTime[i]
      /*
      console.log("RawDocsCount: "+data.RawDocsCount.join('+')+' = '+rawDocsCountTotal)
      console.log("RawDocsSearchTime: "+data.RawDocsSearchTime.join('+')+' = '+rawDocsSearchTimeTotal)
      console.log("ReRankSearchTime: "+data.ReRankSearchTime.join('+')+' = '+reRankSearchTimeTotal)
      console.log("Trial: "+data.trial)
      console.log("CacheHit: "+data.CacheHit)
      */
      ga('send', 'event', 'search', 'RawDocsCount', data.RawDocsCount.join('+') + ' = ' + rawDocsCountTotal, rawDocsCountTotal)
      ga('send', 'event', 'search', 'RawDocsSearchTime', data.RawDocsSearchTime.join('+') + ' = ' + rawDocsSearchTimeTotal, rawDocsSearchTimeTotal)
      ga('send', 'event', 'search', 'ReRankSearchTime', data.ReRankSearchTime.join('+') + ' = ' + reRankSearchTimeTotal, reRankSearchTimeTotal)
      ga('send', 'event', 'search', 'Trial', data.trial, data.trial)
      // ga('send', 'event', 'search', 'CacheHit', data.CacheHit, data.CacheHit)
      // ga('send', 'event', 'search', 'numResult', data.docs.length, data.docs.length)
      document.querySelector('#results').innerHTML = '<div id="status">' + rawDocsCountTotal + ' images searched in ' + ((rawDocsSearchTimeTotal + reRankSearchTimeTotal) / 1000).toFixed(2) + ' seconds</div>'
      if (data.docs.length > 0) {
        $.each(data.docs, function (key, entry) {
          if (safeSearch == false || entry.season != 'Sukebei') {
            var similarity = (100 - parseFloat(entry.diff)).toFixed(1)
            var result = document.createElement('li')
            result.setAttribute('class', 'result')
            result.setAttribute('data-season', entry.season)
            result.setAttribute('data-anime', entry.anime)
            result.setAttribute('data-title', entry.title)
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
            // encodeURIComponent
            var thumbnailLink = '/thumbnail.php?season=' + encodeURIComponent(entry.season) + '&anime=' + encodeURIComponent(entry.anime) + '&file=' + encodeURIComponent(entry.file) + '&t=' + (entry.t) + '&expires=' + entry.expires + '&token=' + entry.tokenthumb
            var opacity = (Math.pow(((100 - parseFloat(entry.diff)) / 100), 4) + 0.2).toFixed(3)
            result.style.opacity = opacity > 1 ? 1 : opacity
            if (formatTime(entry.from) == formatTime(entry.to))
              result.innerHTML = '<a href="#"><span class="title">' + entry.title + '</span><br><span class="ep">EP#' + zeroPad(entry.episode, 2) + '</span> <span class="time">' + formatTime(entry.from) + '</span> <span class="similarity">~' + similarity + '%</span><br><span class="file">' + entry.file + '</span><img src="' + thumbnailLink + '"></a>'
            else
              result.innerHTML = '<a href="#"><span class="title">' + entry.title + '</span><br><span class="ep">EP#' + zeroPad(entry.episode, 2) + '</span> <span class="time">' + formatTime(entry.from) + '-' + formatTime(entry.to) + '</span> <span class="similarity">~' + similarity + '%</span><br><span class="file">' + entry.file + '</span><img src="' + thumbnailLink + '"></a>'
            document.querySelector('#results').appendChild(result)
          // $("#results").append('<div class="highlight"><a class="result" href="#">'+url+'</a> Score:'+entry.diff+'</div>')
          }
        })
        var topResult = '/' + data.docs[0].season + '/' + data.docs[0].anime + '/' + data.docs[0].file + '?start=' + data.docs[0].start + '&end=' + data.docs[0].end + '&t=' + data.docs[0].t
        ga('send', 'event', 'search', 'topResult', topResult, data.docs[0].diff)
        $('.result').click(playfile)
        firstPlay = true

        if (parseFloat(data.docs[0].diff) > 10) { // && data.trial >= 5
          $('#results').prepend('<div id="status">Image not found.<br>But there are visually similar scenes</div>')
        // document.querySelector("#autoplay").checked = false
        } else {
          if (safeSearch == false) {
            // document.querySelector("#autoplay").checked = true
            $('.result')[0].click()
          }
        }
      } else {
        document.querySelector('#results').innerHTML = '<div id="status">No result</div>'
      }
    }, 'json').fail(function (e) {
    if (e.status == 429) {
      ga('send', 'event', 'search', 'recaptcha', 'challenge')
      document.querySelector('#results').innerHTML = ''
      grecaptcha.reset()
      $('.g-recaptcha').removeClass('hidden')
    } else {
      document.querySelector('#results').innerHTML = '<div id="status">Connection to Search Server Failed</div>'
    }
    document.querySelector('#searchBtn').disabled = false
    document.querySelector('#flipBtn').disabled = false
    document.querySelector('#imageURL').disabled = false
  }).complete(function (e) {
    preview.classList.remove('blur')
    document.querySelector('#searchBtn span').classList.remove('glyphicon-refresh')
    document.querySelector('#searchBtn span').classList.remove('spinning')
    document.querySelector('#searchBtn span').classList.add('glyphicon-search')
  })
}
var recaptcha_success = function () {
  ga('send', 'event', 'search', 'recaptcha', 'pass')
  $.post('/search',
    {'g-recaptcha-response': document.querySelector('#g-recaptcha-response').value}, function (data, textStatus) {
      $('.g-recaptcha').addClass('hidden')
      // document.querySelector("#results").innerHTML = '<div id="status">Thanks, you can now search again</div>'
      search()
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
    document.querySelector('#autoplay').checked = false
    $('#safeBtn').children('.glyphicon').removeClass('glyphicon-unchecked')
    $('#safeBtn').children('.glyphicon').addClass('glyphicon-check')
  } else {
    safeSearch = false
    $('#safeBtn').children('.glyphicon').addClass('glyphicon-unchecked')
    $('#safeBtn').children('.glyphicon').removeClass('glyphicon-check')
  }
}

// $("#volume").change(function(){document.querySelector('#player').volume = $("#volume").val();})
$('#mute').change(function () {
  if (document.querySelector('#mute').checked)
    document.querySelector('#player').volume = 0
  else
    document.querySelector('#player').volume = 1
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
  var src = '/' + season + '/' + encodeURIComponent(anime) + '/' + encodeURIComponent(file) + '?start=' + start + '&end=' + end + '&token=' + token + '&expires=' + expires
  document.querySelector('#player').src = src
  if (document.querySelector('#autoplay').checked) {
    time = parseFloat(tfrom) - parseFloat(start)
  // time = time - 0.3
  } else {
    time = parseFloat(t) - parseFloat(start)
  }
  if (time < 0) time = 0
  // player.currentTime = time
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
var loadeddata = function (event) {
  preview.addEventListener('click', playPause)
  player.currentTime = time
  if (document.querySelector('#autoplay').checked) {
    player.play()
  }
}
document.querySelector('#player').addEventListener('loadeddata', loadeddata , false)
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

var originalImage = new Image()
originalImage.crossOrigin = 'anonymous'
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
  // var imageAspectRatio = originalImage.width/originalImage.height
  /*
  if(imageAspectRatio > (4/3 - 0.05) && imageAspectRatio < (4/3 + 0.05) || imageAspectRatio > (16/9 + 0.05)){
      document.querySelector("#fitWidthRadio").checked = true
      fitWidth = false
  }
  else{
      document.querySelector("#fitWidthRadio").checked = false
      fitWidth = true
  }
  */
  changeAspectRatio()
  drawPreview(searchImage)
  document.querySelector('#searchBtn').disabled = false
  document.querySelector('#flipBtn').disabled = false
  document.querySelector('#messageText').classList.add('success')
  document.querySelector('#messageText').innerHTML = ''
  document.querySelector('#results').innerHTML = '<div id="status">Press Search button to begin searching.</div>'
  if (auto) {
    auto = false
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
  imgDataURL = searchImage.toDataURL('image/jpeg', 90)
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
  imgDataURL = searchImage.toDataURL('image/jpeg', 90)
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
  // preview.getContext('2d').drawImage(source, x, y, w, h)
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
  // preview.getContext('2d').drawImage(source, x, y, w, h)
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
  } else {
    // document.querySelector("#results").innerHTML = '<div id="status">Error: File is not an image</div>'
    // return false
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
  // $('<h3>', {text: src.title_english}).appendTo('#info')
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

  var character_ids = []
  var staff_ids = []

  if (src.staff.length > 0) {
    $('<br>', {style: 'clear:both'}).appendTo('#info')
    $('<h3>', {text: 'Staff'}).appendTo('#info')
    $('<div>', {style: 'clear:both; border-bottom:1px solid #666; margin-bottom:3px'}).appendTo('#info')

    var staffTable = $('<table>', {id: 'staff'})
    $.each(src.staff, function (key, entry) {
      staff_ids.push(entry.id)
      var row = $('<tr>')
      var name = ''
      if (entry.language == 'English')
        name = entry.name_first + ' ' + entry.name_last
      else
        name = entry.name_last + ' ' + entry.name_first
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
      character_ids.push(entry.id)
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
      var charName = $('<div>')
      $('<a>', {class: 'character_' + entry.id, href: '//anilist.co/character/' + entry.id, target: '_blank', text: entry.name_last + ' ' + entry.name_first}).appendTo(charName)
      if (entry.actor.length > 0) {
        staff_ids.push(entry.actor[0].id)
        $('<br>').appendTo(charName)
        var name = entry.actor[0].name_last + ' ' + entry.actor[0].name_first
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

  if (staff_ids.length > 0) {
    var request = {}
    request.size = 100
    request.fields = ['name_first_japanese', 'name_last_japanese']
    request.query = {
      'ids': {
        'type': 'staff',
        'values': staff_ids
      }
    }
    $.ajax({
      type: 'POST',
      url: '/staff/',
      dataType: 'json',
      data: JSON.stringify(request),
      async: true,
      cache: false,
      timeout: 2000,
      success: function (data) {
        if (data.hits.total > 0) {
          $.each(data.hits.hits, function (key, entry) {
            var name_japanese = entry.fields.name_last_japanese[0] + entry.fields.name_first_japanese[0]
            if (name_japanese != '') {
              $('.staff_' + entry._id).text(name_japanese)
            }
          })
        }
      },
      error: function (jqxhr, textStatus, error) {
        console.log(error)
      }
    })
  }
  if (character_ids.length > 0) {
    var request = {}
    request.size = 100
    request.fields = ['name_japanese']
    request.query = {
      'ids': {
        'type': 'character',
        'values': character_ids
      }
    }
    $.ajax({
      type: 'POST',
      url: '/character/',
      dataType: 'json',
      data: JSON.stringify(request),
      async: true,
      cache: false,
      timeout: 2000,
      success: function (data) {
        if (data.hits.total > 0) {
          $.each(data.hits.hits, function (key, entry) {
            var name_japanese = entry.fields.name_japanese[0]
            if (name_japanese != '') {
              $('.character_' + entry._id).text(name_japanese)
            }
          })
        }
      },
      error: function (jqxhr, textStatus, error) {
        console.log(error)
      }
    })
  }

  $('<div>', {style: 'clear:both; border-bottom:1px solid #666; margin-bottom:3px'}).appendTo('#info')
  $('<div>', {html: 'Information provided by <a href="https://anilist.co" target="_blank">anilist.co</a>', style: 'float:right;font-size:12px'}).appendTo('#info')
  $('<div>', {style: 'clear:both'}).appendTo('#info')
}
