# whatanime.ga API Docs

This API is still under development. Make sure you stay up-to-date with me and this project. 

## Getting Started
1. Request API token. Contact me via email(soruly@gmail.com) or [Twitter](https://twitter.com/soruly)(@soruly) or [Telegram](https://t.me/soruly)(@soruly).
2. Let me know what you are going to do with it. (Playing, experimenting and testing is fine)
3. Read the docs and try the API
4. Give feedbacks and suggestions

## Quick Start

Once you've got your API token, check your identity first.

`curl https://whatanime.ga/api/me?token=${your_token}`

Now base64 encode an image file and POST to /search.

`curl -X POST https://whatanime.ga/api/search?token=${your_token} -d "image=data:image/jpeg;base64,$(base64 -w 0 search_image.jpg)"`

Or if you prefer using [HTTPie](https://httpie.org/) :

`http --body https://whatanime.ga/api/me?token=${your_token}`

`http --body --form POST https://whatanime.ga/api/search?token=${your_token} image="data:image/jpeg;base64,$(base64 -w 0 search_image.jpg)"`

## Me

Let you verify that you have a valid user account. And see your search quota limit for your account.

URL

```
GET /api/me?token={your_api_token} HTTP/1.1
Host: whatanime.ga
```

Example Response

```
{
  "user_id": 1001,
  "email": "soruly@gmail.com",
  "quota": 10,
  "quota_ttl": 60
}
```

Returns HTTP 403 if API token is invalid.

Returns HTTP 401 if API token is missing.

## Search

Seach request should be POST as HTTP Form, not JSON

```
POST /api/search?token={your_api_token} HTTP/1.1
Content-Type: application/x-www-form-urlencoded; charset=UTF-8
Host: whatanime.ga

image={Base64 Encoded Image}&filter=*
```

| Fields        | Value         | Notes  |
| ------------- |---------------| -------|
| image         | String (Required) | Base64 Encoded Image |
| filter        | Number (Optional) | Limit search to specific anilist ID. |

<p class="warning">
  Note that there is a hard limit of 1MB post size. You should ensure your Base64 encoded image is < 1MB. Otherwise the server responds with HTTP 413 (Request Entity Too Large).
</p>

jQuery Example
```
$.post('/search',
  {
    'image': searchImage.toDataURL('image/jpeg', 0.8)
  },
  function (data, textStatus) {

  }
);

```

Example Response
```
{
  "RawDocsCount": 3555648,
  "RawDocsSearchTime": 14056,
  "ReRankSearchTime": 1182,
  "CacheHit": false,
  "trial": 1,
  "quota": 8,
  "expire": 53,
  "docs": [
    {
      "from": 663.17,
      "to": 665.42,
      "anilist_id": 98444,
      "at": 665.08,
      "season": "2018-01",
      "anime": "搖曳露營",
      "filename": "[Ohys-Raws] Yuru Camp - 05 (AT-X 1280x720 x264 AAC).mp4",
      "episode": 5,
      "tokenthumb": "bB-8KQuoc6u-1SfzuVnDMw",
      "similarity": 1,
      "title": "ゆるキャン△",
      "title_native": "ゆるキャン△",
      "title_chinese": "搖曳露營",
      "title_english": "Laid-Back Camp",
      "title_romaji": "Yuru Camp△",
      "mal_id": 34798,
      "synonyms": [
        "Yurucamp",
        "Yurukyan△"
      ],
      "synonyms_chinese": [],
      "is_adult": false
    }
  ]
}
```

| Fields        | Meaning       | Value  |
| ------------- |---------------| -------|
| RawDocsCount       | Total number of frames searched | Number |
| RawDocsSearchTime  | Time taken to retrieve the frames from database (sum of all cores) | Number |
| ReRankSearchTime   | Time taken to compare the frames (sum of all cores) | Number |
| CacheHit  | Whether the search result is cached. (Results are cached by extraced image feature) | Boolean |
| trial | Number of times searched | Number |
| quota | Number of search quota remaining | Number |
| expire | Time until quota resets (seconds) | Number |
| docs | Search results (see table below) | Array of Objects |


| Fields           | Meaning       | Value  |
| ---------------- |---------------| -------|
| from             | Starting time of the matching scene | Number (seconds, in 3 decimal places)
| to               | Ending time of the matching scene | Number (seconds, in 3 decimal places)
| at               | Exact time of the matching scene | Number (seconds, in 3 decimal places)
| episode          | The extracted episode number from filename | Number, "OVA/OAD", "Special", ""
| similarity       | Similarity compared to the search image | Number (float between 0-1)
| anilist_id       | The matching [AniList](https://anilist.co/) ID | Number or null
| mal_id           | The matching [MyAnimeList](https://myanimelist.net/) ID | Number or null
| is_adult         | Whether the anime is hentai | Boolean
| ~~title~~        | (deprecated, do not use this) | |
| title_native     | Native (Japanese) title | String or null (Can be empty string)
| title_chinese    | Chinese title | String or null (Can be empty string)
| title_english    | English title | String or null (Can be empty string)
| title_romaji     | Title in romaji | String
| synonyms         | Alternate english titles | Array of String or []
| synonyms_chinese | Alternate chinese titles | Array of String or []
| ~~season~~       | (deprecated, do not use this) | |
| ~~anime~~        | (deprecated, do not use this) | |
| filename         | The filename of file where the match is found | String
| tokenthumb       | A token for generating preview | String

<p class="tip">
  Search results with similarity lower than 85% are probably incorrect result (just similar, not a match). It's up to you to decide the cut-off value.
</p>

Notes:
- If multiple results are found in the same file, near the same timecode and has similarity > 98%, results are grouped as one, using `from` and `to` to indicate the starting time and ending time of that scene.
- `episode` is only an estimated number extraction from `filename`, it may fail and return empty string
- With `anilist_id`, you may get more anime info from `https://anilist.co/anime/{anilist_id}` . Read [AniList API](https://github.com/joshstar/AniList-API-Docs) for more information.
- The list of chinese translation can be obtained from [anilist-chinese](https://github.com/soruly/anilist-chinese)
- Search results would be cached for 5-60 minutes. Higher accuracy cache longer.

### Search Quota

The `/api/search` endpoint has a request limit.

Each API token is limited to 10 search per minute.

Once the limit is reached. Server would respond HTTP 429 Too Many Requests, with a text message showing when the quota will reset.

Example
```
Search quota exceeded. Please wait 87 seconds.
```

<p class="warning">
  Note that the server is served via cloudflare, their servers may have some rate limit control and anti-flood mechanism.
</p>

<p class="tip">
  It is recommended to handle any exception cases properly in your application, such as rate limit, timeouts and network errors.
</p>

### Previews

With `tokenthumb`, you can access image preview of the matched scene. (not quite accurate due to timecode and seeking method)

`https://whatanime.ga/thumbnail.php?anilist_id=${anilist_id}&file=${encodeURIComponent(filename)}&t=${at}&token=${tokenthumb}`

There is a 3 second video preview of the matched scene. (1 seconds before and 2 seconds ahead)

`https://whatanime.ga/preview.php?anilist_id=${anilist_id}&file=${encodeURIComponent(filename)}&t=${at}&token=${tokenthumb}`
