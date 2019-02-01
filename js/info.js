"use strict";

var showAnilistInfo = function (anilistID) {
  $.get("/info?anilist_id=" + anilistID, function (data, textStatus) {
    if (data.length > 0) {
      displayInfo(data[0]);
      document.querySelector("#info").style.display = "inline-block";
      document.querySelector("#info").style.visibility = "visible";
      document.querySelector("#info").style.opacity = 1;
    }
  }, "json");
};

var displayInfo = function (src) {
  $("<h1>", {
    "text": src.title.native,
    "style": "font-size:1.5em"
  }).appendTo("#info");
  $("<h2>", {"text": src.title.romaji}).appendTo("#info");
  $("<h2>", {"text": src.title.english}).appendTo("#info");
  $("<h2>", {"text": src.title.chinese}).appendTo("#info");
  $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:13px"}).appendTo("#info");

  if (src.coverImage.large) {
    var div = $("<div>", {"id": "poster"}).appendTo("#info");

    $("<a>", {
      "href": "//anilist.co/anime/" + src.id
    }).appendTo("#poster");
    $("<img>", {"src": src.coverImage.large.replace("http:", "")}).appendTo("#poster a");
    div.appendTo("#info");
  }

  var naturalText = "";

  if (src.duration) {
    if (src.episodes === 1) {
      naturalText += src.duration + " minutes";
    }
  }

  if (src.episodes) {
    if (src.format !== "MOVIE") {
      naturalText += src.episodes + " episode ";
    }
  }
  if (src.format) {
    naturalText += src.format;
  }
  naturalText += " Anime";

  if (src.duration) {
    if (src.episodes > 1) {
      naturalText += " (" + src.duration + " minutes each)";
    }
  }

  var strStartDate = src.startDate && src.startDate.year && src.startDate.month && src.startDate.day ? src.startDate.year + "-" + src.startDate.month + "-" + src.startDate.day : null;
  var strEndDate = src.endDate && src.endDate.year && src.endDate.month && src.endDate.day ? src.endDate.year + "-" + src.endDate.month + "-" + src.endDate.day : null;

  if (strStartDate && strEndDate) {
    if (src.format === "MOVIE") {
      if (strStartDate === strEndDate) {
        naturalText += ". Released on " + strStartDate;
      } else {
        naturalText += ". Released during " + strStartDate + " to " + strEndDate;
      }
    } else if (strStartDate === strEndDate) {
      naturalText += ". Released on " + strStartDate;
    } else {
      naturalText += ". Airing from " + strStartDate + " to " + strEndDate;
    }
  } else if (strStartDate) {
    if (src.format === "TV" || src.format === "TV_SHORT") {
      naturalText += ". Airing since " + strStartDate;
    }
  }

  naturalText += ". ";
  $("<div>", {
    "id": "naturalText",
    "text": naturalText
  }).appendTo("#info");

  $("<table>", {"id": "table"}).appendTo("#info");
  var row = $("<tr>");

  $("<td>", {"text": "Score"}).appendTo(row);
  if (src.averageScore > 0) {
    $("<td>", {"text": parseFloat(src.averageScore).toFixed(1)}).appendTo(row);
  } else {
    $("<td>", {"text": "-"}).appendTo(row);
  }
  row.appendTo("#info #table");

  var row = $("<tr>");

  $("<td>", {"text": "Popularity"}).appendTo(row);
  $("<td>", {"text": src.popularity}).appendTo(row);
  row.appendTo("#info #table");

  var row = $("<tr>");

  $("<td>", {"text": "Drop rate"}).appendTo(row);
  if (src.popularity > 0) {
    $("<td>", {"text": (src.stats.statusDistribution.filter(function(e){return e.status==="DROPPED"})[0].amount / src.popularity * 100).toFixed(1) + "%"}).appendTo(row);
  } else {
    $("<td>", {"text": "-"}).appendTo(row);
  }
  row.appendTo("#info #table");

  if (src.genres.length > 0) {
    var row = $("<tr>");

    $("<td>", {"text": "Genre"}).appendTo(row);
    $.each(src.genres, function (key, entry) {
      src.genres[key] = entry;
    });
    $("<td>", {"text": src.genres.join(", ")}).appendTo(row);
    row.appendTo("#info #table");
  }

  if (src.studios && src.studios && src.studios.edges.length > 0) {
    var row = $("<tr>");

    $("<td>", {"text": "Studio"}).appendTo(row);
    var td = $("<td>");

    $.each(src.studios.edges, function(key, entry) {
      if (entry.node.siteUrl) {
        $("<a>", {
          "href": entry.node.siteUrl,
          "text": entry.node.name
        }).appendTo(td);
      } else {
        $("<span>", {"text": entry.node.name}).appendTo(td);
      }
      $("<br>").appendTo(td);
    });
    td.appendTo(row);
    row.appendTo("#info #table");
  }

  if (src.synonyms.length > 0) {
    var row = $("<tr>");

    $("<td>", {"text": "Alias"}).appendTo(row);
    $("<td>", {"html": src.synonyms.join("<br>")}).appendTo(row);
    row.appendTo("#info #table");
  }
  if (src.synonyms_chinese.length > 0) {
    var row = $("<tr>");

    $("<td>", {"text": "Alias"}).appendTo(row);
    $("<td>", {"html": Array.isArray(src.synonyms_chinese) ? src.synonyms_chinese.join("<br>") : "<br>"}).appendTo(row);
    row.appendTo("#info #table");
  }

  if (src.externalLinks && src.externalLinks.length > 0) {
    var row = $("<tr>");

    $("<td>", {"text": "External Links"}).appendTo(row);
    var td = $("<td>");

    $.each(src.externalLinks, function (key, entry) {
      $("<a>", {
        "href": entry.url,
        "text": entry.site + " "
      }).appendTo(td);
      $("<br>").appendTo(td);
    });
    td.appendTo(row);
    row.appendTo("#info #table");
  }

  if (src.staff && src.staff.edges && src.staff.edges.length > 0) {
    $("<br>", {"style": "clear:both"}).appendTo("#info");
    $("<h3>", {"text": "Staff"}).appendTo("#info");
    $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:3px"}).appendTo("#info");

    var staffTable = $("<table>", {"id": "staff"});

    $.each(src.staff.edges, function(key, entry) {
      var row = $("<tr>");
      var name = entry.node.name.native;

      if (!name && entry.node.name.first && entry.node.name.last){
        name = entry.node.name.last + " " + entry.node.name.first;
      }
      $("<td>", {"text": entry.role}).appendTo(row);

      var nameTD = $("<td>");

      $("<a>", {
        "class": "staff_" + entry.node.id,
        "href": "//anilist.co/staff/" + entry.node.id,
        "text": name
      }).appendTo(nameTD);
      nameTD.appendTo(row);
      row.appendTo(staffTable);
    });
    staffTable.appendTo("#info");
  }

  if (src.trailer && src.trailer && src.trailer.site === "youtube") {
    $("<br>", {"style": "clear:both"}).appendTo("#info");
    $("<h3>", {"text": "Youtube PV"}).appendTo("#info");
    $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:3px"}).appendTo("#info");
    $("<div>", {"html": "<iframe id=\"youtube\" width=\"100%\" height=\"360\" src=\"https://www.youtube.com/embed/" + src.trailer.id + "\" frameborder=\"0\" allowfullscreen></iframe>"}).appendTo("#info");
  }
  $("<br>", {"style": "clear:both"}).appendTo("#info");
  $("<h3>", {"text": "Synopses"}).appendTo("#info");
  $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:3px"}).appendTo("#info");
  $("<div>", {
    "html": src.description,
    "style": "text-align:justify"
  }).appendTo("#info");

  if (src.characters && src.characters.edges && src.characters.edges.length > 0) {
    $("<br>", {"style": "clear:both"}).appendTo("#info");
    $("<h3>", {"text": "Characters"}).appendTo("#info");
    $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:3px"}).appendTo("#info");
    var characterDIV = $("<div>");

    $.each(src.characters.edges, function(key, entry) {
      var charDIV = $("<div>", {"class": "character"});
      var charImgDiv = $("<div>");

      if (entry.node.image.large === "//anilist.co") {
        entry.node.image.large = "//anilist.co/img/dir/anime/reg/noimg.jpg";
      }
      if (entry.node.image.medium === "//anilist.co") {
        entry.node.image.medium = "//anilist.co/img/dir/anime/med/noimg.jpg";
      }
      var charIMG = $("<a>", {
        "href": entry.node.image.large.replace("http:", "")
      }).appendTo(charImgDiv);

      $("<div>", {"style": "background-image:url(" + entry.node.image.medium.replace("http:", "") + ")"}).appendTo(charIMG);
      charImgDiv.appendTo(charDIV);
      var charNameDiv = $("<div>");
      var charName = $("<div>");
      var char_name = entry.node.name.native;
      if (!char_name && entry.node.name.first && entry.node.name.last){
        char_name = entry.node.name.last + " " + entry.node.name.first;
      }
      charName = $("<div>");

      $("<a>", {
        "class": "character_" + entry.node.id,
        "href": "//anilist.co/character/" + entry.node.id,
        "text": char_name
      }).appendTo(charName);
      if (entry.voiceActors && entry.voiceActors.length > 0) {
        $("<br>").appendTo(charName);
        var name = entry.voiceActors[0].name.native;
        if (!name && entry.voiceActors[0].name.first && entry.voiceActors[0].name.last){
          name = entry.voiceActors[0].name.last + " " + entry.voiceActors[0].name.first;
        }
        charName.append(document.createTextNode("(CV: "));
        $("<a>", {
          "class": "staff_" + entry.voiceActors[0].id,
          "href": "//anilist.co/staff/" + entry.voiceActors[0].id,
          "text": name
        }).appendTo(charName);
        charName.append(document.createTextNode(")"));
      }
      charName.appendTo(charNameDiv);
      charNameDiv.appendTo(charDIV);
      charDIV.appendTo(characterDIV);
    });
    characterDIV.appendTo("#info");
  }

  $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:3px"}).appendTo("#info");
  $("<div>", {
    "html": "Information provided by <a href=\"https://anilist.co\">anilist.co</a>",
    "style": "float:right;font-size:12px"
  }).appendTo("#info");
  $("<div>", {"style": "clear:both"}).appendTo("#info");
};
