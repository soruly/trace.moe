if (document.querySelector(".alert")) {
  document.querySelector(".alert").onclick = () => {
    document.querySelector(".alert").style.display = "none";
  };
}

const updateURLParam = () => {
  if (document.querySelector("#imageURL").value) {
    history.replaceState(
      null,
      null,
      `/?url=${encodeURIComponent(
        document.querySelector("#imageURL").value.replace(/ /g, "%20")
      )}`
    );
  } else {
    history.replaceState(null, null, "/");
  }
};

const formatTime = (timeInSeconds) => {
  const sec_num = parseInt(timeInSeconds, 10);
  const hours = Math.floor(sec_num / 3600);
  const minutes = Math.floor((sec_num - hours * 3600) / 60);
  const seconds = sec_num - hours * 3600 - minutes * 60;
  return [
    hours < 10 ? `0${hours}` : hours,
    minutes < 10 ? `0${minutes}` : minutes,
    seconds < 10 ? `0${seconds}` : seconds,
  ].join(":");
};

const zeroPad = (n, width) => {
  if (n.length === undefined) {
    return n.toString();
  }
  return n.length >= width
    ? n.toString()
    : new Array(width - n.toString().length + 1).join("0") + n;
};

const player = document.querySelector("#player");
const preview = document.querySelector("#preview");
const originalImage = document.querySelector("#originalImage");

originalImage.onload = () => {
  resetAll();
  // clear the input if user upload/paste image
  if (/^blob:/.test(originalImage.src)) {
    document.querySelector("#imageURL").value = "";
  }
  // updateURLParam();
  prepareSearchImage();
};

window.addEventListener("load", (event) => {
  if (originalImage.dataset.url) {
    originalImage.src = originalImage.dataset.url;
  }
});

let imgData;
const search = async () => {
  document.querySelector("#loading").classList.remove("hidden");
  document.querySelector("#loader").classList.add("ripple");
  document.querySelector("#progressBarControl").style.visibility = "hidden";
  document.querySelector("#soundBtn").style.visibility = "hidden";
  document.querySelector("#soundBtn").classList.remove("glyphicon-volume-up");
  document.querySelector("#soundBtn").classList.remove("glyphicon-volume-off");
  document.querySelector("#soundBtn").classList.add("glyphicon-volume-off");
  player.volume = 0;
  player.muted = true;

  document.querySelector("#fileNameDisplay").innerText = "";
  document.querySelector("#timeCodeDisplay").innerText = "";

  document
    .querySelector("#searchBtn span")
    .classList.remove("glyphicon-search");
  document.querySelector("#searchBtn span").classList.add("glyphicon-refresh");
  document.querySelector("#searchBtn span").classList.add("spinning");
  resetInfo();
  animeInfo = null;
  document.querySelector("#player").pause();
  preview.removeEventListener("click", playPause);
  document.querySelector("#results").innerHTML =
    '<div id="status">Submitting image for searching...</div>';
  document.querySelector("#searchBtn").disabled = true;
  document.querySelector("#imageURL").disabled = true;

  const formData = new FormData();
  formData.append("image", imgData);
  const queryString = [
    document
      .querySelector("#cutBordersBtn .glyphicon")
      .classList.contains("glyphicon-check")
      ? "cutBorders=1"
      : "cutBorders=",
    document.querySelector("#anilistFilter").value
      ? `anilistID=${document.querySelector("#anilistFilter").value}`
      : "anilistID=",
  ].join("&");
  const res = await fetch(`https://api.trace.moe/search?${queryString}`, {
    method: "POST",
    body: formData,
  });

  document
    .querySelector("#searchBtn span")
    .classList.remove("glyphicon-refresh");
  document.querySelector("#searchBtn span").classList.remove("spinning");
  document.querySelector("#searchBtn span").classList.add("glyphicon-search");

  document.querySelector("#searchBtn").disabled = false;
  document.querySelector("#imageURL").disabled = false;

  document.querySelector("#loading").classList.add("hidden");
  document.querySelector("#loader").classList.remove("ripple");
  document.querySelector("#searchBtn").disabled = false;
  document.querySelector("#imageURL").disabled = false;
  document.querySelector("#results").innerHTML = "";

  if (res.status === 429) {
    document.querySelector("#results").innerHTML =
      '<div id="status">You have searched too many times, please try again later.</div>';
    return;
  }
  if (res.status !== 200) {
    document.querySelector("#results").innerHTML =
      '<div id="status">Connection to Search Server Failed</div>';
    return;
  }
  const { frameCount, result } = await res.json();

  document.querySelector(
    "#results"
  ).innerHTML += `<div id="status">${frameCount
    .toString()
    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")} frames searched.</div>`;

  if (result.length === 0) {
    document.querySelector("#results").innerHTML =
      '<div id="status">No result</div>';
    return;
  }

  result.forEach((entry, index) => {
    const result = document.createElement("li");

    result.classList.add("result");
    if (entry.is_adult) {
      result.classList.add("hidden");
    }

    let title_display = entry.title_romaji;
    if (navigator.language.includes("ja")) {
      title_display = entry.title_native || entry.title_romaji;
    }
    if (navigator.language.includes("zh")) {
      title_display = entry.title_chinese || entry.title_romaji;
    }

    result.innerHTML = [
      `<div>`,
      `<div class="text">`,
      `<span class="title">${title_display}</span><br>`,
      entry.episode
        ? `<span class="ep">EP#${zeroPad(entry.episode, 2)}</span>`
        : "",
      formatTime(entry.from) === formatTime(entry.to)
        ? `<span class="time">${formatTime(entry.from)}</span>`
        : `<span class="time">${formatTime(entry.from)}-${formatTime(
            entry.to
          )}</span>`,
      `<span class="similarity">~${(entry.similarity * 100).toFixed(
        2
      )}%</span><br>`,
      `<span class="file">${entry.file}</span>`,
      `</div>`,
      entry.similarity > 0.9 && index < 5
        ? `<div class="thumb" style="min-height:166px"><img src="${entry.image}"></div>`
        : "",
      `</div>`,
    ].join(" ");

    const opacity = Math.pow(entry.similarity, 4) + 0.2;
    result.style.opacity = opacity > 1 ? 1 : opacity;

    result.addEventListener("click", (e) => {
      playfile(result, entry.video, entry.file, entry.anilist_id, entry.from);
    });

    document.querySelector("#results").appendChild(result);
  });

  if (result.find((e) => e.is_adult)) {
    const nswfMsg = document.createElement("div");
    nswfMsg.style.textAlign = "center";
    const showNSFWBtn = document.createElement("button");
    showNSFWBtn.type = "button";
    showNSFWBtn.classList.add("btn", "btn-default", "btn-sm", "btn-primary");
    showNSFWBtn.innerText = `Click here to show ${
      result.filter((e) => e.is_adult).length
    } NSFW results`;
    showNSFWBtn.addEventListener("click", () => {
      document
        .querySelectorAll(".result.hidden")
        .forEach((e) => e.classList.remove("hidden"));
      nswfMsg.classList.add("hidden");
    });
    nswfMsg.appendChild(showNSFWBtn);
    document.querySelector("#results").appendChild(nswfMsg);
  }

  if (!document.querySelectorAll(".result")[0].classList.contains("hidden")) {
    document.querySelectorAll(".result")[0].click();
  }
};

document.querySelector("#searchBtn").addEventListener("click", search);

let fetchImageDelay;

document.querySelector("#imageURL").addEventListener("input", function () {
  clearTimeout(fetchImageDelay);
  document.querySelector("#messageText").classList.remove("error");
  document.querySelector("#messageText").classList.remove("success");
  if (document.querySelector("#imageURL").value.length) {
    if (document.querySelector("form").checkValidity()) {
      fetchImageDelay = setTimeout(function () {
        document.querySelector("#messageText").innerHTML =
          '<span class="glyphicon glyphicon-repeat spinning"></span>';
        originalImage.src =
          "https://trace.moe/image-proxy?url=" +
          encodeURIComponent(
            document.querySelector("#imageURL").value.replace(/ /g, "%20")
          );
        history.replaceState(
          null,
          null,
          "/?url=" +
            encodeURIComponent(
              document.querySelector("#imageURL").value.replace(/ /g, "%20")
            )
        );
      }, 500);
    } else {
      document.querySelector("#submit").click();
    }
  }
});

document.querySelector("#soundBtn").addEventListener("click", () => {
  document.querySelector("#soundBtn").classList.toggle("glyphicon-volume-up");
  document.querySelector("#soundBtn").classList.toggle("glyphicon-volume-off");
  if (
    document
      .querySelector("#soundBtn")
      .classList.contains("glyphicon-volume-up")
  ) {
    player.volume = 1;
    player.muted = false;
  } else {
    player.volume = 0;
    player.muted = true;
  }
});

document.querySelector("#cutBordersBtn").addEventListener("click", () => {
  document
    .querySelector("#cutBordersBtn .glyphicon")
    .classList.toggle("glyphicon-unchecked");
  document
    .querySelector("#cutBordersBtn .glyphicon")
    .classList.toggle("glyphicon-check");
});

// document.querySelector("#jcBtn").addEventListener("click", () => {
//   document
//     .querySelector("#jcBtn .glyphicon")
//     .classList.toggle("glyphicon-unchecked");
//   document
//     .querySelector("#jcBtn .glyphicon")
//     .classList.toggle("glyphicon-check");
// });

let drawVideoPreview = function () {
  preview_heartbeat = window.requestAnimationFrame(drawVideoPreview);
  if (preview.getContext("2d")) {
    preview
      .getContext("2d")
      .drawImage(player, 0, 0, preview.width, preview.height);
  }
};

preview.addEventListener("contextmenu", function (e) {
  e.preventDefault();
});

let preview_heartbeat;
let time;
let animeInfo = null;
let playfile = async (target, videoURL, fileName, anilistID, timeCode) => {
  [].forEach.call(document.querySelectorAll(".result"), function (result) {
    result.classList.remove("active");
  });

  target.classList.add("active");
  document.querySelector("#player").pause();
  window.cancelAnimationFrame(preview_heartbeat);

  if (animeInfo !== anilistID) {
    animeInfo = anilistID;
    resetInfo();
    showAnilistInfo(anilistID);
  }

  document.querySelector("#loading").classList.remove("hidden");
  document.querySelector("#loader").classList.add("ripple");
  let response = await fetch(`${videoURL}&size=l`);
  document.querySelector("#player").src = URL.createObjectURL(
    await response.blob()
  );
  let duration = response.headers.get("x-video-duration");

  document.querySelector("#fileNameDisplay").innerText = fileName;
  document.querySelector("#timeCodeDisplay").innerText =
    formatTime(timeCode) + "/" + formatTime(duration);
  let left = (parseFloat(timeCode) / parseFloat(duration)) * 640 - 6;

  document.querySelector("#progressBarControl").style.visibility = "visible";
  document.querySelector("#progressBarControl").style.left = left + "px";
  document.querySelector("#soundBtn").style.visibility = "visible";
};

let playPause = function () {
  if (player.paused) {
    player.play();
  } else {
    player.pause();
  }
};

let loadedmetadata = function () {
  let aspectRatio;

  if (player.height === 0 && player.width === 0) {
    aspectRatio = player.videoWidth / player.videoHeight;
  } else {
    aspectRatio = player.width / player.height;
  }

  preview.width = 640;
  preview.height = 640 / aspectRatio;
  document.querySelector("#loading").style.height = preview.height + "px";
  document.querySelector("#loader").style.top =
    (preview.height - 800) / 2 + "px";
  preview.addEventListener("click", playPause);
  player.oncanplaythrough = () => {
    document.querySelector("#loading").classList.add("hidden");
    document.querySelector("#loader").classList.remove("ripple");
    window.cancelAnimationFrame(preview_heartbeat);
    preview_heartbeat = window.requestAnimationFrame(drawVideoPreview);
  };
  player.play();
};

document
  .querySelector("#player")
  .addEventListener("loadedmetadata", loadedmetadata, false);

let searchImage = document.createElement("canvas");

let resetAll = function () {
  preview.width = 640;
  preview.height = 360;
  document.querySelector("#progressBarControl").style.visibility = "hidden";
  document.querySelector("#soundBtn").style.visibility = "hidden";
  document.querySelector("#soundBtn").classList.remove("glyphicon-volume-up");
  document.querySelector("#soundBtn").classList.remove("glyphicon-volume-off");
  document.querySelector("#soundBtn").classList.add("glyphicon-volume-off");
  player.volume = 0;
  player.muted = true;

  document.querySelector("#fileNameDisplay").innerText = "";
  document.querySelector("#timeCodeDisplay").innerText = "";
  document.querySelector("#loading").style.height = preview.height + "px";
  document.querySelector("#loader").style.top =
    (preview.height - 800) / 2 + "px";
  preview.getContext("2d").fillStyle = "#FFFFFF";
  preview.getContext("2d").fillRect(0, 0, preview.width, preview.height);
  resetInfo();
  document.querySelector("#player").pause();
  preview.removeEventListener("click", playPause);
  window.cancelAnimationFrame(preview_heartbeat);
};

originalImage.onerror = function () {
  document.querySelector("#messageText").classList.add("error");
  document.querySelector("#messageText").innerText = "";
};

let prepareSearchImage = function () {
  let img = originalImage;
  let imageAspectRatio = img.width / img.height;

  searchImage.width = 640;
  searchImage.height = 640 / imageAspectRatio;

  searchImage
    .getContext("2d")
    .drawImage(
      img,
      0,
      0,
      img.width,
      img.height,
      0,
      0,
      searchImage.width,
      searchImage.height
    );

  preview.height = searchImage.height;
  document.querySelector("#loading").style.height = preview.height + "px";
  document.querySelector("#loader").style.top =
    (preview.height - 800) / 2 + "px";

  preview
    .getContext("2d")
    .drawImage(searchImage, 0, 0, searchImage.width, searchImage.height);

  searchImage.toBlob(
    function (blob) {
      imgData = blob;

      document.querySelector("#searchBtn").disabled = false;
      document.querySelector("#messageText").classList.add("success");
      document.querySelector("#messageText").innerHTML = "";
      document.querySelector("#results").innerHTML =
        '<div id="status">Press Search button to begin searching.</div>';
      if (document.querySelector("#autoSearch").checked) {
        document.querySelector("#messageText").classList.remove("error");
        document.querySelector("#messageText").classList.remove("success");
        document.querySelector("#messageText").innerHTML = "";
        document.querySelector("#autoSearch").checked = false;
        search();
      }
    },
    "image/jpeg",
    80
  );
};

let handleFileSelect = function (evt) {
  evt.stopPropagation();
  evt.preventDefault();

  let file;

  if (evt.dataTransfer) {
    file = evt.dataTransfer.files[0];
  } else {
    file = evt.target.files[0];
  }

  if (file) {
    document.querySelector("#results").innerHTML =
      '<div id="status">Reading File...</div>';
    if (file.type.match("image.*")) {
      URL.revokeObjectURL(originalImage.src);
      originalImage.src = URL.createObjectURL(file);
    } else {
      document.querySelector("#results").innerHTML =
        '<div id="status">Error: File is not an image</div>';
      return false;
    }
  }
};

let handleDragOver = function (evt) {
  evt.stopPropagation();
  evt.preventDefault();
  evt.dataTransfer.dropEffect = "copy";
};

let dropZone = document.querySelector("#preview");

dropZone.addEventListener("dragover", handleDragOver, false);
dropZone.addEventListener("drop", handleFileSelect, false);

document
  .querySelector("#file")
  .addEventListener("change", handleFileSelect, false);

let CLIPBOARD = new CLIPBOARD_CLASS("preview");

function CLIPBOARD_CLASS(canvas_id) {
  let _self = this;
  let canvas = document.getElementById(canvas_id);
  let ctx = document.getElementById(canvas_id).getContext("2d");
  let ctrl_pressed = false;
  let pasteCatcher;
  let paste_mode;

  document.addEventListener(
    "paste",
    function (e) {
      _self.paste_auto(e);
    },
    false
  );

  this.init = (function () {
    pasteCatcher = document.createElement("div");
    pasteCatcher.setAttribute("contenteditable", "");
    pasteCatcher.style.cssText = "opacity:0;position:fixed;top:0px;left:0px;";
    document.body.appendChild(pasteCatcher);
    pasteCatcher.addEventListener(
      "DOMSubtreeModified",
      function () {
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
      },
      false
    );
  })();
  // default paste action
  this.paste_auto = function (e) {
    if (
      e.target !== document.querySelector("#imageURL") &&
      e.target !== document.querySelector("#anilistFilter")
    ) {
      paste_mode = "";
      pasteCatcher.innerHTML = "";

      if (e.clipboardData) {
        let items = e.clipboardData.items;

        if (items) {
          paste_mode = "auto";
          // access data directly
          for (let i = 0; i < items.length; i++) {
            if (items[i].type.indexOf("image") !== -1) {
              // image
              let blob = items[i].getAsFile();
              let URLObj = window.URL || window.webkitURL;
              let source = URLObj.createObjectURL(blob);

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
    let pastedImage = new Image();

    pastedImage.onload = function () {
      ctx.drawImage(pastedImage, 0, 0);
    };
    originalImage.src = source;
  };
}

let resetInfo = function () {
  document.querySelector("#info").innerHTML = "";
  document.querySelector("#info").style.display = "none";
  document.querySelector("#info").style.visibility = "hidden";
  document.querySelector("#info").style.opacity = 0;
};

window.onerror = function (message, source, lineno, colno, error) {
  if (error) {
    message = error.stack;
  }
  if (typeof ga === "function") {
    ga("send", "event", "error", message);
  }
};
