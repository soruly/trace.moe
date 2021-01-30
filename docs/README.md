# trace.moe API Docs

This API is always under development. Please stay up-to-date with this project via Discord or Patreon, and <a href="https://www.patreon.com/soruly">donate</a> <ExternalLinkIcon /> to support this project!

## Search

You have 2 ways to use the API for searching:

Search by image URL

```bash
curl -s https://trace.moe/api/search?url=https://foobar/baz.jpg
```

This method is easiest but it only works for images that are publicly available.

Otherwise, you must send the image data:

Upload image by FORM

```
curl -F "image=@your_search_image.jpg" https://trace.moe/api/search
```

POST image by FORM

```bash
curl -X POST https://trace.moe/api/search -d "image=$(base64 -w 0 your_search_image.jpg)"
```

POST image by JSON

```bash
curl -X POST https://trace.moe/api/search -H "Content-Type: application/json" -d '{ "image" : "'$(base64 -w 0 your_search_image.jpg)'" }'
```

| Fields | Value             | Notes                                |
| ------ | ----------------- | ------------------------------------ |
| image  | String (Required) | Base64 Encoded Image                 |
| filter | Number (Optional) | Limit search to specific anilist ID. |

### Search Image Format

- Supported image format is any format supported by `javax.imageio.ImageIO` (i.e. jpg, png, bmp, gif)
- For Animated GIF, only first frame is used for searching
- [Data URI](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/Data_URIs) prefix like `data:image/jpeg;base64,` is optional. Only data part after the comma is used. This is to maintain compatibility with [toDataURL()](https://developer.mozilla.org/en-US/docs/Web/API/HTMLCanvasElement/toDataURL) with HTML5 canvas.
- Binary file upload is not yet supported

<Note type="warning">

Note that there is a hard limit of 10MB post size. You should ensure your Base64 encoded image is < 10MB. Otherwise the server responds with HTTP 413 (Request Entity Too Large).

</Note>

Javascript Example

```javascript
var img = document.querySelector("img"); // select image from DOM
var canvas = document.createElement("canvas");
canvas.width = img.naturalWidth;
canvas.height = img.naturalHeight;
var ctx = canvas.getContext("2d");
ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

fetch("https://trace.moe/api/search", {
  method: "POST",
  body: JSON.stringify({ image: canvas.toDataURL("image/jpeg", 0.8) }),
  headers: { "Content-Type": "application/json" },
})
  .then((res) => res.json())
  .then((result) => {
    console.log(result);
  });
```

Example Response

```json
{
  "RawDocsCount": 3555648,
  "RawDocsSearchTime": 14056,
  "ReRankSearchTime": 1182,
  "CacheHit": false,
  "trial": 1,
  "limit": 9,
  "limit_ttl": 60,
  "quota": 150,
  "quota_ttl": 86400,
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
      "similarity": 0.9563952960290518,
      "title": "ゆるキャン△",
      "title_native": "ゆるキャン△",
      "title_chinese": "搖曳露營",
      "title_english": "Laid-Back Camp",
      "title_romaji": "Yuru Camp△",
      "mal_id": 34798,
      "synonyms": ["Yurucamp", "Yurukyan△"],
      "synonyms_chinese": [],
      "is_adult": false
    }
  ]
}
```

| Fields            | Meaning                                                                              | Value            |
| ----------------- | ------------------------------------------------------------------------------------ | ---------------- |
| RawDocsCount      | Total number of frames searched                                                      | Number           |
| RawDocsSearchTime | Time taken to retrieve the frames from database (sum of all cores)                   | Number           |
| ReRankSearchTime  | Time taken to compare the frames (sum of all cores)                                  | Number           |
| CacheHit          | Whether the search result is cached. (Results are cached by extracted image feature) | Boolean          |
| trial             | Number of times searched                                                             | Number           |
| limit             | Number of search limit remaining                                                     | Number           |
| limit_ttl         | Time until limit resets (seconds)                                                    | Number           |
| quota             | Number of search quota remaining                                                     | Number           |
| quota_ttl         | Time until quota resets (seconds)                                                    | Number           |
| docs              | Search results (see table below)                                                     | Array of Objects |

| Fields           | Meaning                                                 | Value                                 |
| ---------------- | ------------------------------------------------------- | ------------------------------------- |
| from             | Starting time of the matching scene                     | Number (seconds, in 2 decimal places) |
| to               | Ending time of the matching scene                       | Number (seconds, in 2 decimal places) |
| at               | Exact time of the matching scene                        | Number (seconds, in 2 decimal places) |
| episode          | The extracted episode number from filename              | Number, "OVA/OAD", "Special", ""      |
| similarity       | Similarity compared to the search image                 | Number (float between 0-1)            |
| anilist_id       | The matching [AniList](https://anilist.co/) ID          | Number                                |
| mal_id           | The matching [MyAnimeList](https://myanimelist.net/) ID | Number or null                        |
| is_adult         | Whether the anime is hentai                             | Boolean                               |
| title_native     | Native (Japanese) title                                 | String or null (Can be empty string)  |
| title_chinese    | Chinese title                                           | String or null (Can be empty string)  |
| title_english    | English title                                           | String or null (Can be empty string)  |
| title_romaji     | Title in romaji                                         | String                                |
| synonyms         | Alternate english titles                                | Array of String or []                 |
| synonyms_chinese | Alternate chinese titles                                | Array of String or []                 |
| filename         | The filename of file where the match is found           | String                                |
| tokenthumb       | A token for generating preview                          | String                                |

<Note type="tip">

Search results with similarity lower than 87% are probably incorrect result (just similar, not a match). It's up to you to decide the cut-off value.

</Note>

### Search Result Notes

- Results are always sorted by similarity, from most similar to least similar
- If multiple results are found in the same file, near the same timecode and has similarity > 98%, results are grouped as one, using `from` and `to` to indicate the starting time and ending time of that scene.
- `episode` is only an estimated number extraction from `filename`, it may fail and return empty string
- With `anilist_id`, you may get more anime info from `https://anilist.co/anime/{anilist_id}` . Read [AniList API](https://github.com/AniList/ApiV2-GraphQL-Docs) for more information.
- The list of chinese translation can be obtained from [anilist-chinese](https://github.com/soruly/anilist-chinese)
- API would return HTTP 400 if your search image is empty
- API would return HTTP 403 if are using an invalid token
- API would return HTTP 429 if are using requesting too fast
- API would return HTTP 500 or HTTP 503 if something went wrong in backend

If your image is malformed, you may got an error message (HTTP 500) like this:

```
"Error reading image from URL: http://192.168.2.11/pic/1549186015.8901.jpg: http://192.168.2.11/pic/1549186015.8901.jpg"
```

These image URLs are for debugging use, and is not accessible from internet. You may send this to admin to inspect what went wrong. All images send to server are deleted after 24 hours.

Aside from the JSON response or the [/me](#Me) endpoint, you can also get the current limit from HTTP response header.

```
x-whatanime-limit: 9
x-whatanime-limit-ttl: 60
x-whatanime-quota: 150
x-whatanime-quota-ttl: 86400
```

Once the limit is reached. Server would respond HTTP 429 Too Many Requests, with a double quoted string showing when the quota will reset.

Example

```
"Search limit exceeded. Please wait 87 seconds."
```

<Note type="tip">

All error messages are double quoted string in order to ensure they are always valid JSON format.

</Note>

## Previews

With `tokenthumb` you obtained from [/search](#Search), you can get previews of the matched scene. (not 100% accurate due to timecode and seeking method)

### Image and Video Preview

Media preview is served by [trace.moe-media](https://github.com/soruly/trace.moe-media)

```
https://media.trace.moe/image/${anilist_id}/${encodeURIComponent(filename)}?t=${at}&token=${tokenthumb}`
```

It can generate image or video preview of 3 sizes: `size=l` (large), `size=m` (medium, default), `size=s` (small)

```
https://media.trace.moe/image/${anilist_id}/${encodeURIComponent(filename)}?t=${at}&token=${tokenthumb}&size=s`
```

Video previews are cut on timestamp boundaries of a scene.

```
https://media.trace.moe/video/${anilist_id}/${encodeURIComponent(filename)}?t=${at}&token=${tokenthumb}`
```

If you prefer getting a mute video, add `mute` in your querystring

```
https://media.trace.moe/video/${anilist_id}/${encodeURIComponent(filename)}?t=${at}&token=${tokenthumb}&mute`
```

## Me

Let you check the search quota and limit for your account (or IP address).

```bash
curl https://trace.moe/api/me
```

Example Response

```json
{
  "user_id": 1001,
  "email": "soruly@gmail.com",
  "limit": 9,
  "limit_ttl": 45,
  "quota": 1000,
  "quota_ttl": 86400,
  "user_limit": 10,
  "user_limit_ttl": 60,
  "user_quota": 1000,
  "user_quota_ttl": 86400
}
```

| Fields         | Meaning                                      | Value            |
| -------------- | -------------------------------------------- | ---------------- |
| user_id        | Null or Account ID                           | Null or Number   |
| email          | IP address or your Account Email             | String           |
| limit          | Current remaining limit for your account now | Number           |
| limit_ttl      | Time until limit reset                       | Number (seconds) |
| quota          | Current remaining limit for your account now | Number           |
| quota_ttl      | Time until quota reset                       | Number (seconds) |
| user_limit     | Rate limit associated with your account      | Number           |
| user_limit_ttl | Usually set to 60 (1 minute)                 | Number (seconds) |
| user_quota     | Quota associated with your account           | Number           |
| user_quota_ttl | Usually set to 86400 (1 day)                 | Number (seconds) |

## Rate limit and Search Quota

Every IP address has a rate limit of 10/minute, counting API and WebUI together. There is no limit to daily search quota.
