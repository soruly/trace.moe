"use strict";

var showAnilistInfo = function (season, anime) {
  $.get("/info?season=" + encodeURIComponent(season) + "&anime=" + encodeURIComponent(anime), function (data, textStatus) {
    if (data.length > 0) {
      displayInfo(data[0]);
      document.querySelector("#info").style.visibility = "visible";
      document.querySelector("#info").style.opacity = 1;
    }
  }, "json");
};

var displayInfo = function (src) {
  $("<h1>", {
    "text": src.title_japanese,
    "style": "font-size:1.5em"
  }).appendTo("#info");
  $("<h2>", {"text": src.title_romaji}).appendTo("#info");
  $("<h2>", {"text": src.title_english}).appendTo("#info");
  $("<h2>", {"text": src.title_chinese}).appendTo("#info");
  $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:13px"}).appendTo("#info");

  if (src.image_url_lge) {
    var div = $("<div>", {"id": "poster"}).appendTo("#info");

    $("<a>", {
      "href": "//anilist.co/anime/" + src.id,
      "target": "_blank"
    }).appendTo("#poster");
    $("<img>", {"src": src.image_url_lge.replace("http:", "")}).appendTo("#poster a");
    div.appendTo("#info");
  }

  var naturalText = "";

  if (src.duration) {
    if (src.total_episodes === 1) {
      naturalText += src.duration + " minutes";
    }
  }

  if (src.total_episodes) {
    if (src.type !== "Movie") {
      naturalText += src.total_episodes + " episode ";
    }
  }
  if (src.type) {
    naturalText += src.type;
  }
  naturalText += " Anime";

  if (src.duration) {
    if (src.total_episodes > 1) {
      naturalText += " (" + src.duration + " minutes each)";
    }
  }

  if (src.start_date && src.end_date) {
    if (src.type === "Movie") {
      if (src.start_date === src.end_date) {
        naturalText += ". Released on " + src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, "$1-$2-$3");
      } else {
        naturalText += ". Released during " + src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, "$1-$2-$3") + " to " + src.end_date.replace(/(\d+)-(\d+)-(\d+)T.*/, "$1-$2-$3");
      }
    } else if (src.start_date === src.end_date) {
      naturalText += ". Released on " + src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, "$1-$2-$3");
    } else {
      naturalText += ". Airing from " + src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, "$1-$2-$3") + " to " + src.end_date.replace(/(\d+)-(\d+)-(\d+)T.*/, "$1-$2-$3");
    }
  } else if (src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, "$1") !== "1970") {
    if (src.type === "TV" || src.type === "TV Short") {
      naturalText += ". Airing since " + src.start_date.replace(/(\d+)-(\d+)-(\d+)T.*/, "$1-$2-$3");
    }
  }

  naturalText += "。";
  $("<div>", {
    "id": "naturalText",
    "text": naturalText
  }).appendTo("#info");

  $("<table>", {"id": "table"}).appendTo("#info");
  var row = $("<tr>");

  $("<td>", {"text": "Score"}).appendTo(row);
  if (src.average_score > 0) {
    $("<td>", {"text": parseFloat(src.average_score).toFixed(1)}).appendTo(row);
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
    $("<td>", {"text": (src.list_stats.dropped / src.popularity * 100).toFixed(1) + "%"}).appendTo(row);
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

  if (src.studio.length > 0) {
    var row = $("<tr>");

    $("<td>", {"text": "Studio"}).appendTo(row);
    var td = $("<td>");

    $.each(src.studio, function (key, entry) {
      if (entry.studio_wiki) {
        $("<a>", {
          "href": entry.studio_wiki,
          "target": "_blank",
          "text": entry.studio_name
        }).appendTo(td);
      } else {
        $("<span>", {"text": entry.studio_name}).appendTo(td);
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
    $("<td>", {"html": src.synonyms_chinese.join("<br>")}).appendTo(row);
    row.appendTo("#info #table");
  }

  if (src.classification) {
    var row = $("<tr>");

    $("<td>", {"text": "Rating"}).appendTo(row);
    $("<td>", {"text": src.classification}).appendTo(row);
    row.appendTo("#info #table");
  }

  if (src.external_links.length > 0) {
    var row = $("<tr>");

    $("<td>", {"text": "External Links"}).appendTo(row);
    var td = $("<td>");

    $.each(src.external_links, function (key, entry) {
      $("<a>", {
        "href": entry.url,
        "target": "_blank",
        "text": entry.site + " "
      }).appendTo(td);
      $("<br>").appendTo(td);
    });
    td.appendTo(row);
    row.appendTo("#info #table");
  }

  if (src.staff.length > 0) {
    $("<br>", {"style": "clear:both"}).appendTo("#info");
    $("<h3>", {"text": "Staff"}).appendTo("#info");
    $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:3px"}).appendTo("#info");

    var staffTable = $("<table>", {"id": "staff"});

    $.each(src.staff, function (key, entry) {
      var row = $("<tr>");
      var name = entry.name_first;

      if (entry.name_last) {
        name += " " + entry.name_last;
      }
      if (entry.names_first_japanese && entry.names_last_japanese) {
        name = entry.names_first_japanese + entry.names_last_japanese;
      }
      if (entry.role) {
        $("<td>", {"text": entry.role}).appendTo(row);
      } else {
        $("<td>", {"text": entry.role.replace("Theme Song Performance", "Theme Song Performance")}).appendTo(row);
      }

      var nameTD = $("<td>");

      $("<a>", {
        "class": "staff_" + entry.id,
        "href": "//anilist.co/staff/" + entry.id,
        "target": "_blank",
        "text": name
      }).appendTo(nameTD);
      nameTD.appendTo(row);
      row.appendTo(staffTable);
    });
    staffTable.appendTo("#info");
  }

  if (src.youtube_id) {
    $("<br>", {"style": "clear:both"}).appendTo("#info");
    $("<h3>", {"text": "Youtube PV"}).appendTo("#info");
    $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:3px"}).appendTo("#info");
    $("<div>", {"html": "<iframe id=\"youtube\" width=\"100%\" height=\"360\" src=\"https://www.youtube.com/embed/" + src.youtube_id + "\" frameborder=\"0\" allowfullscreen></iframe>"}).appendTo("#info");
  }
  $("<br>", {"style": "clear:both"}).appendTo("#info");
  $("<h3>", {"text": "Synopses"}).appendTo("#info");
  $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:3px"}).appendTo("#info");
  $("<div>", {
    "html": src.description,
    "style": "text-align:justify"
  }).appendTo("#info");

  if (src.characters.length > 0) {
    $("<br>", {"style": "clear:both"}).appendTo("#info");
    $("<h3>", {"text": "Characters"}).appendTo("#info");
    $("<div>", {"style": "clear:both; border-bottom:1px solid #666; margin-bottom:3px"}).appendTo("#info");
    var characterDIV = $("<div>", {"style": "display:inline-block"});

    $.each(src.characters, function (key, entry) {
      var charDIV = $("<div>", {"class": "character"});
      var charImgDiv = $("<div>");

      if (entry.image_url_lge === "//anilist.co") {
        entry.image_url_lge = "//anilist.co/img/dir/anime/reg/noimg.jpg";
      }
      if (entry.image_url_med === "//anilist.co") {
        entry.image_url_med = "//anilist.co/img/dir/anime/med/noimg.jpg";
      }
      var charIMG = $("<a>", {
        "href": entry.image_url_lge.replace("http:", ""),
        "target": "_blank"
      }).appendTo(charImgDiv);

      $("<div>", {"style": "background-image:url(" + entry.image_url_med.replace("http:", "") + ")"}).appendTo(charIMG);
      charImgDiv.appendTo(charDIV);
      var charNameDiv = $("<div>");
      var charName = $("<div>");
      var char_name = entry.name_first;

      if (entry.name_last) {
        char_name += " " + entry.name_last;
      }
      if (entry.name_japanese) {
        char_name = entry.name_japanese;
      }
      charName = $("<div>");

      $("<a>", {
        "class": "character_" + entry.id,
        "href": "//anilist.co/character/" + entry.id,
        "target": "_blank",
        "text": char_name
      }).appendTo(charName);
      if (entry.actor.length > 0) {
        $("<br>").appendTo(charName);
        var name = entry.actor[0].name_first;

        if (entry.actor[0].name_last) {
          name += " " + entry.actor[0].name_last;
        }
        if (entry.actor[0].names_first_japanese) {
          name = entry.actor[0].names_first_japanese;
        }
        if (entry.actor[0].names_first_japanese && entry.actor[0].names_last_japanese) {
          name = entry.actor[0].names_first_japanese + entry.actor[0].names_last_japanese;
        }
        charName.append(document.createTextNode("(CV: "));
        $("<a>", {
          "class": "staff_" + entry.actor[0].id,
          "href": "//anilist.co/staff/" + entry.actor[0].id,
          "target": "_blank",
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
    "html": "Information provided by <a href=\"https://anilist.co\" target=\"_blank\">anilist.co</a>",
    "style": "float:right;font-size:12px"
  }).appendTo("#info");
  $("<div>", {"style": "clear:both"}).appendTo("#info");
};
