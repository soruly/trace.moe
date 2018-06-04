"use strict";

$(document).ready(function () {
  var ua = navigator.userAgent;

  document.querySelector("#mute").checked = true;
  document.querySelector("#player").muted = true;

  document.querySelector("#autoplay").checked = true;
  document.querySelector("#loop").checked = false;

  location.search.substr(1).split("&").forEach(function (param) {
    var key = param.split("=")[0];
    var val = param.split("=")[1];

    if (key === "autoplay") {
      document.querySelector("#autoplay").checked = val !== "0";
    }
    if (key === "mute") {
      document.querySelector("#mute").checked = val !== "0";
    }
    if (key === "loop") {
      document.querySelector("#loop").checked = val !== "0";
    }
  });

  document.querySelector("#autoplay").onchange = updateURLParam;
  document.querySelector("#mute").onchange = updateURLParam;
  document.querySelector("#loop").onchange = updateURLParam;
});

document.querySelector("#mute").addEventListener("change", function () {
  document.querySelector("#player").muted = document.querySelector("#mute").checked;
});

var updateURLParam = function () {
  var urlString = "";
  var params = [];

  if (document.querySelector("#autoplay").checked !== true) {
    params.push("autoplay=0");
  }
  if (document.querySelector("#mute").checked === true) {
    params.push("mute");
  }
  if (document.querySelector("#loop").checked === true) {
    params.push("loop");
  }
  urlString += params.sort().join("&");
  if (document.querySelector("#imageURL").value) {
    if (params.length > 0) {
      urlString += "&";
    }
    urlString += "url=" + encodeURI(document.querySelector("#imageURL").value.replace(/ /g, "%20"));
  }
  if (urlString.length > 0) {
    history.replaceState(null, null, "/?" + urlString);
  } else {
    history.replaceState(null, null, "/");
  }
};

var formatTime = function (timeInSeconds) {
  var sec_num = parseInt(timeInSeconds, 10);
  var hours = Math.floor(sec_num / 3600);
  var minutes = Math.floor((sec_num - hours * 3600) / 60);
  var seconds = sec_num - hours * 3600 - minutes * 60;

  if (hours < 10) {
    hours = "0" + hours;
  }
  if (minutes < 10) {
    minutes = "0" + minutes;
  }
  if (seconds < 10) {
    seconds = "0" + seconds;
  }
  var timestring = hours + ":" + minutes + ":" + seconds;

  return timestring;
};

var zeroPad = function (n, width) {
  if (n.length === undefined) return n.toString();
  return n.length >= width ? n.toString() : new Array(width - n.toString().length + 1).join("0") + n;
};

var player = document.querySelector("#player");
var preview = document.querySelector("#preview");
var originalImage = document.querySelector("#originalImage");

player.volume = 0.5;

var imgDataURL;
var searchRequest;
var search = function (trial, prev_result) {
  if (!trial) {
    trial = 0;
  }
  if (searchRequest && searchRequest.readyState !== 4) {
    searchRequest.abort();
  }
  document.querySelector("#loading").classList.remove("hidden");
  document.querySelector("#progressBarControl").style.visibility = "hidden";
  document.querySelector("#fileNameDisplay").innerText = "";
  document.querySelector("#timeCodeDisplay").innerText = "";
  if (navigator.userAgent.indexOf("Chrome") || !navigator.userAgent.indexOf("Safari")) {
    document.querySelector("#loader").classList.add("ripple");
  }
  document.querySelector("#searchBtn span").classList.remove("glyphicon-search");
  document.querySelector("#searchBtn span").classList.add("glyphicon-refresh");
  document.querySelector("#searchBtn span").classList.add("spinning");
  if (document.querySelector("#search2Btn span")) {
    document.querySelector("#search2Btn span").classList.remove("glyphicon-search");
    document.querySelector("#search2Btn span").classList.add("glyphicon-refresh");
    document.querySelector("#search2Btn span").classList.add("spinning");
  }
  resetInfo();
  animeInfo = null;
  document.querySelector("#player").pause();
  preview.removeEventListener("click", playPause);
  if (trial === 0) {
    document.querySelector("#results").innerHTML = "<div id=\"status\">Submitting image for searching...</div>";
  }
  document.querySelector("#searchBtn").disabled = true;
  if (document.querySelector("#search2Btn span")) {
    document.querySelector("#search2Btn").disabled = true;
  }
  document.querySelector("#flipBtn").disabled = true;
  document.querySelector("#imageURL").disabled = true;

  searchRequest = $.post("/search",
    {
      "data": imgDataURL,
      "filter": document.querySelector("#seasonSelector").value,
      "trial": trial
    }, function (data) {
      document.querySelector("#loading").classList.add("hidden");
      document.querySelector("#loader").classList.remove("ripple");
      document.querySelector("#searchBtn").disabled = false;
      if (document.querySelector("#search2Btn span")) {
        document.querySelector("#search2Btn").disabled = false;
      }
      document.querySelector("#flipBtn").disabled = false;
      document.querySelector("#imageURL").disabled = false;
      document.querySelector("#results").innerHTML = "";
      if (prev_result) {
        data.RawDocsCount += prev_result.RawDocsCount;
        data.RawDocsSearchTime += prev_result.RawDocsSearchTime;
        data.ReRankSearchTime += prev_result.ReRankSearchTime;
      }

      document.querySelector("#results").innerHTML += "<div id=\"status\">" + data.RawDocsCount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " images searched.</div>";
      if (prev_result) {
        data.docs = data.docs.concat(prev_result.docs);
      }
      data.docs.sort(function (a, b) {
        return a.diff - b.diff;
      });
      if (data.docs.length > 0) {
        data.docs.forEach(function (entry, index) {
          if (document.querySelector("#safeBtn .glyphicon").classList.contains("glyphicon-check") === false || entry.is_adult === false) {
            var similarity = (100 - parseFloat(entry.diff)).toFixed(1);
            var result = document.createElement("li");

            result.setAttribute("class", "result");
            result.setAttribute("data-season", entry.season);
            result.setAttribute("data-title", entry.title);
            result.setAttribute("data-title-native", entry.title_native);
            result.setAttribute("data-title-chinese", entry.title_chinese);
            result.setAttribute("data-title-english", entry.title_english);
            result.setAttribute("data-title-romaji", entry.title_romaji);
            result.setAttribute("data-file", entry.file);
            result.setAttribute("data-episode", entry.episode);
            result.setAttribute("data-start", entry.start);
            result.setAttribute("data-end", entry.end);
            result.setAttribute("data-token", entry.token);
            result.setAttribute("data-tokenthumb", entry.tokenthumb);
            result.setAttribute("data-expires", entry.expires);
            result.setAttribute("data-from", entry.from);
            result.setAttribute("data-to", entry.to);
            result.setAttribute("data-t", entry.t);
            result.setAttribute("data-anilist-id", entry.anilist_id);

            var thumbnailLink = "/thumbnail.php?anilist_id=" + entry.anilist_id + "&file=" + encodeURIComponent(entry.file) + "&t=" + entry.t + "&token=" + entry.tokenthumb;
            var opacity = (Math.pow((100 - parseFloat(entry.diff)) / 100, 4) + 0.2).toFixed(3);

            result.style.opacity = opacity > 1 ? 1 : opacity;
            var title_display = entry.title_romaji;

            if (navigator.language.indexOf("ja") === 0) {
              title_display = entry.title_native || entry.title_romaji;
            }
            if (navigator.language.indexOf("zh") === 0) {
              title_display = entry.title_chinese || entry.title_romaji;
            }
            var thumbnailImage = (trial >= 2 || parseFloat(data.docs[0].diff) < 10) && index < 5 ? "<div style=\"height:166px\"><img src=\"" + thumbnailLink + "\" onload=\"this.parentElement.style.height = null;\"></div>" : "";

            if (formatTime(entry.from) === formatTime(entry.to)) {
              result.innerHTML = "<a href=\"#\"><span class=\"title\">" + title_display + "</span><br><span class=\"ep\">EP#" + zeroPad(entry.episode, 2) + "</span> <span class=\"time\">" + formatTime(entry.from) + "</span> <span class=\"similarity\">~" + similarity + "%</span><br><span class=\"file\">" + entry.file + "</span>" + thumbnailImage + "</a>";
            } else {
              result.innerHTML = "<a href=\"#\"><span class=\"title\">" + title_display + "</span><br><span class=\"ep\">EP#" + zeroPad(entry.episode, 2) + "</span> <span class=\"time\">" + formatTime(entry.from) + "-" + formatTime(entry.to) + "</span> <span class=\"similarity\">~" + similarity + "%</span><br><span class=\"file\">" + entry.file + "</span>" + thumbnailImage + "</a>";
            }
            document.querySelector("#results").appendChild(result);
          }
        });
        $(".result").click(playfile);

        if (parseFloat(data.docs[0].diff) > 10) {
          if (trial < 2) {
            search(trial + 1, data);
          } else if (trial < 5) {
            $("#results").prepend("<div style=\"text-align:center\"><button id=\"search2Btn\" type=\"button\" class=\"btn btn-default btn-sm btn-primary\"><span class=\"glyphicon glyphicon-search\"></span> Keep Searching</button></div>");
            $("#search2Btn").click(function () {
              search(trial + 1, data);
            });
          }
        } else {
          if (trial < 5) {
            $("#results").prepend("<div style=\"text-align:center\"><button id=\"search2Btn\" type=\"button\" class=\"btn btn-default btn-sm btn-primary\"><span class=\"glyphicon glyphicon-search\"></span> Keep Searching</button></div>");
            $("#search2Btn").click(function () {
              search(trial + 1, data);
            });
          }
          if (document.querySelector("#safeBtn .glyphicon").classList.contains("glyphicon-check") === false) {
            $(".result")[0].click();
          }
        }
      } else {
        document.querySelector("#results").innerHTML = "<div id=\"status\">No result</div>";
      }
    }, "json").fail(function (e) {
    if (e.status === 429) {
      document.querySelector("#results").innerHTML = "<div id=\"status\">You have searched too much, try again in 10 minutes.</div>";
    } else {
      document.querySelector("#results").innerHTML = "<div id=\"status\">Connection to Search Server Failed</div>";
    }
    document.querySelector("#searchBtn").disabled = false;
    if (document.querySelector("#search2Btn span")) {
      document.querySelector("#search2Btn").disabled = false;
    }
    document.querySelector("#flipBtn").disabled = false;
    document.querySelector("#imageURL").disabled = false;
  }).always(function () {
    document.querySelector("#searchBtn span").classList.remove("glyphicon-refresh");
    document.querySelector("#searchBtn span").classList.remove("spinning");
    document.querySelector("#searchBtn span").classList.add("glyphicon-search");
    if (document.querySelector("#search2Btn span")) {
      document.querySelector("#search2Btn span").classList.remove("glyphicon-refresh");
      document.querySelector("#search2Btn span").classList.remove("spinning");
      document.querySelector("#search2Btn span").classList.add("glyphicon-search");
    }
  });
};

document.querySelector("#searchBtn").addEventListener("click", function () {
  search();
});

document.querySelector("#flipBtn").addEventListener("click", function () {
  document.querySelector("#flipBtn .glyphicon").classList.toggle("glyphicon-unchecked");
  document.querySelector("#flipBtn .glyphicon").classList.toggle("glyphicon-check");
  resetAll();
  prepareSearchImage();
});

var fetchImageDelay;

document.querySelector("#imageURL").addEventListener("input", function () {
  clearTimeout(fetchImageDelay);
  document.querySelector("#messageText").classList.remove("error");
  document.querySelector("#messageText").classList.remove("success");
  if (document.querySelector("#imageURL").value.length) {
    if (document.querySelector("form").checkValidity()) {
      fetchImageDelay = setTimeout(function () {
        document.querySelector("#messageText").innerHTML = "<span class=\"glyphicon glyphicon-repeat spinning\"></span>";
        originalImage.src = "https://image.whatanime.ga/imgproxy?url=" + document.querySelector("#imageURL").value.replace(/ /g, "%20");
        history.replaceState(null, null, "/?url=" + encodeURI(document.querySelector("#imageURL").value.replace(/ /g, "%20")));
      }, 500);
    } else {
      document.querySelector("#submit").click();
    }
  }
});


document.querySelector("#safeBtn").addEventListener("click", function () {
  document.querySelector("#safeBtn .glyphicon").classList.toggle("glyphicon-unchecked");
  document.querySelector("#safeBtn .glyphicon").classList.toggle("glyphicon-check");
});

var drawVideoPreview = function () {
  preview_heartbeat = window.requestAnimationFrame(drawVideoPreview);
  preview.getContext("2d").drawImage(player, 0, 0, preview.width, preview.height);
};

preview.addEventListener("contextmenu", function (e) {
  e.preventDefault();
});

var preview_heartbeat;
var time;
var animeInfo = null;
var playfile = function () {
  [].forEach.call(document.querySelectorAll(".result"), function (result) {
    result.classList.remove("active");
  });

  this.classList.add("active");
  document.querySelector("#player").pause();
  window.cancelAnimationFrame(preview_heartbeat);

  preview_heartbeat = window.requestAnimationFrame(drawVideoPreview);
  var season = this.getAttribute("data-season");
  var file = this.getAttribute("data-file");
  var start = this.getAttribute("data-start");
  var end = this.getAttribute("data-end");
  var token = this.getAttribute("data-token");
  var tfrom = this.getAttribute("data-from");
  var t = this.getAttribute("data-t");
  var anilistID = this.getAttribute("data-anilist-id");
  var src = "/" + anilistID + "/" + encodeURIComponent(file) + "?start=" + start + "&end=" + end + "&token=" + token;

  $.get("/duration.php?anilist_id=" + anilistID + "&file=" + encodeURIComponent(file) + "&token=" + token, function (duration) {
    document.querySelector("#fileNameDisplay").innerText = file;
    document.querySelector("#timeCodeDisplay").innerText = formatTime(t) + "/" + formatTime(duration);
    var left = (parseFloat(t) / parseFloat(duration) * 640) - 6;
    document.querySelector("#progressBarControl").style.visibility = "visible";
    document.querySelector("#progressBarControl").style.left = left + "px";
  });

  document.querySelector("#loading").classList.remove("hidden");
  document.querySelector("#player").src = src;
  if (document.querySelector("#autoplay").checked) {
    time = parseFloat(tfrom) - parseFloat(start);
  } else {
    time = parseFloat(t) - parseFloat(start);
  }
  if (time < 0) {
    time = 0;
  }
  if (typeof ga === "function") {
    ga("send", "event", "playfile", "src", "/" + anilistID + "/" + file, t);
  }

  if (animeInfo !== anilistID) {
    animeInfo = anilistID;
    resetInfo();
    showAnilistInfo(anilistID);
  }
};

var playPause = function () {
  if (player.currentTime < time) {
    document.querySelector("#player").currentTime = time;
  }
  if (player.paused) {
    player.play();
  } else {
    player.pause();
  }
};
var canPlayThrough = function () {
  document.querySelector("#loading").classList.add("hidden");
  document.querySelector("#loader").classList.remove("ripple");
};
var loadedmetadata = function () {
  var aspectRatio;

  if (player.height === 0 && player.width === 0) {
    aspectRatio = player.videoWidth / player.videoHeight;
  } else {
    aspectRatio = player.width / player.height;
  }

  preview.width = 640;
  preview.height = 640 / aspectRatio;
  document.querySelector("#loading").style.height = preview.height + "px";
  document.querySelector("#loader").style.top = (preview.height - 800) / 2 + "px";
  preview.addEventListener("click", playPause);
  player.currentTime = time;
  if (document.querySelector("#autoplay").checked) {
    player.oncanplaythrough = canPlayThrough;
    player.play();
  }
};

document.querySelector("#player").addEventListener("loadedmetadata", loadedmetadata, false);
var ended = function () {
  if (document.querySelector("#loop").checked) {
    document.querySelector("#player").currentTime = time - 0.3;
    player.play();
  } else {
    document.querySelector("#player").currentTime = time;
  }
};

document.querySelector("#player").addEventListener("ended", ended, false);

var searchImage = document.createElement("canvas");

var resetAll = function () {
  preview.width = 640;
  preview.height = 360;
  document.querySelector("#progressBarControl").style.visibility = "hidden";
  document.querySelector("#fileNameDisplay").innerText = "";
  document.querySelector("#timeCodeDisplay").innerText = "";
  document.querySelector("#loading").style.height = preview.height + "px";
  document.querySelector("#loader").style.top = (preview.height - 800) / 2 + "px";
  preview.getContext("2d").fillStyle = "#FFFFFF";
  preview.getContext("2d").fillRect(0, 0, preview.width, preview.height);
  resetInfo();
  document.querySelector("#player").pause();
  preview.removeEventListener("click", playPause);
  window.cancelAnimationFrame(preview_heartbeat);
};

originalImage.onload = function () {
  resetAll();
  prepareSearchImage();
};
originalImage.onerror = function () {
  document.querySelector("#messageText").classList.add("error");
  document.querySelector("#messageText").innerText = "";
};

var prepareSearchImage = function () {
  var imageAspectRatio = originalImage.width / originalImage.height;

  searchImage.width = 640;
  searchImage.height = 640 / imageAspectRatio;

  searchImage.getContext("2d").drawImage(originalImage, 0, 0, originalImage.width, originalImage.height, 0, 0, searchImage.width, searchImage.height);

  if (document.querySelector("#flipBtn .glyphicon").classList.contains("glyphicon-check")) {
    searchImage.getContext("2d").save();
    searchImage.getContext("2d").translate(searchImage.width, 0);
    searchImage.getContext("2d").scale(-1, 1);
    searchImage.getContext("2d").drawImage(searchImage, 0, 0);
    searchImage.getContext("2d").restore();
  }

  preview.height = searchImage.height;
  document.querySelector("#loading").style.height = preview.height + "px";
  document.querySelector("#loader").style.top = (preview.height - 800) / 2 + "px";

  preview.getContext("2d").drawImage(searchImage, 0, 0, searchImage.width, searchImage.height);

  imgDataURL = searchImage.toDataURL("image/jpeg", 0.8);

  document.querySelector("#searchBtn").disabled = false;
  document.querySelector("#flipBtn").disabled = false;
  document.querySelector("#messageText").classList.add("success");
  document.querySelector("#messageText").innerHTML = "";
  document.querySelector("#results").innerHTML = "<div id=\"status\">Press Search button to begin searching.</div>";
  if (document.querySelector("#autoSearch").checked) {
    document.querySelector("#autoSearch").checked = false;
    search();
  }
};

var handleFileSelect = function (evt) {
  evt.stopPropagation();
  evt.preventDefault();

  var file;

  if (evt.dataTransfer) {
    file = evt.dataTransfer.files[0];
  } else {
    file = evt.target.files[0];
  }

  if (file) {
    document.querySelector("#results").innerHTML = "<div id=\"status\">Reading File...</div>";
    if (file.type.match("image.*")) {
      var reader = new FileReader();

      reader.onload = (function () {
        return function (e) {
          originalImage.src = e.target.result;
        };
      }(file));
      reader.readAsDataURL(file);
    } else {
      document.querySelector("#results").innerHTML = "<div id=\"status\">Error: File is not an image</div>";
      return false;
    }
  }
};

var handleDragOver = function (evt) {
  evt.stopPropagation();
  evt.preventDefault();
  evt.dataTransfer.dropEffect = "copy";
};

var dropZone = document.querySelector("#preview");

dropZone.addEventListener("dragover", handleDragOver, false);
dropZone.addEventListener("drop", handleFileSelect, false);

document.querySelector("#file").addEventListener("change", handleFileSelect, false);

var CLIPBOARD = new CLIPBOARD_CLASS("preview");

function CLIPBOARD_CLASS (canvas_id) {
  var _self = this;
  var canvas = document.getElementById(canvas_id);
  var ctx = document.getElementById(canvas_id).getContext("2d");
  var ctrl_pressed = false;
  var pasteCatcher;
  var paste_mode;

  document.addEventListener("paste", function (e) {
    _self.paste_auto(e);
  }, false);

  this.init = (function () {

    pasteCatcher = document.createElement("div");
    pasteCatcher.setAttribute("contenteditable", "");
    pasteCatcher.style.cssText = "opacity:0;position:fixed;top:0px;left:0px;";
    document.body.appendChild(pasteCatcher);
    pasteCatcher.addEventListener("DOMSubtreeModified", function () {
      if (paste_mode === "auto" || ctrl_pressed === false) {
        return true;
      }
      if (pasteCatcher.children.length === 1) {
        if (pasteCatcher.firstElementChild.src) {
          _self.paste_createImage(pasteCatcher.firstElementChild.src);
        }
      }
      // register cleanup after some time.
      setTimeout(function () {
        pasteCatcher.innerHTML = "";
      }, 20);
    }, false);
  }());
  // default paste action
  this.paste_auto = function (e) {
    if (e.target !== document.querySelector("#imageURL") && e.target !== document.querySelector("#seasonSelector")) {
      paste_mode = "";
      pasteCatcher.innerHTML = "";

      if (e.clipboardData) {
        var items = e.clipboardData.items;

        if (items) {
          paste_mode = "auto";
          // access data directly
          for (var i = 0; i < items.length; i++) {
            if (items[i].type.indexOf("image") !== -1) {
              // image
              var blob = items[i].getAsFile();
              var URLObj = window.URL || window.webkitURL;
              var source = URLObj.createObjectURL(blob);

              this.paste_createImage(source);
            }
          }
          e.preventDefault();
        }
      }
    }
  };
  // draw image
  this.paste_createImage = function (source) {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    var pastedImage = new Image();

    pastedImage.onload = function () {
      ctx.drawImage(pastedImage, 0, 0);
    };
    originalImage.src = source;
  };
}

var resetInfo = function () {
  document.querySelector("#info").innerHTML = "";
  document.querySelector("#info").style.visibility = "hidden";
  document.querySelector("#info").style.opacity = 0;
};

