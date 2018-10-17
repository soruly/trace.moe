# whatanime.ga API Docs

This API is still under development. Make sure you stay up-to-date with me and this project. 

## Quick Start

POST a base64 encoded image to /search.

`curl -X POST https://whatanime.ga/api/search -d "image=data:image/jpeg;base64,$(base64 -w 0 search_image.jpg)"`

## Rate limit and Search Quota

You can use the API with / without an API token. The difference is the limit when you access the `/api/search` endpoint.

When no API token is given, the system limit search by IP address, and count both search via API and webpage together.

|              | via Webpage / via API without token | Registered Developers | Patreons      |
|--------------|-------------------------------------|-----------------------|---------------|
| Rate Limit   | 10/minute                           | 10/minute             | 10-30/minute  |
| Search quota | 150/day                             | 1000/day              | 1000-3000/day |

If you need more search quota, send me email (soruly@gmail.com) to become registered devlopers.

<p class="warning">
  Since this API is still beta, rate limits are subjected to change.
</p>

## Me

Let you check the search quota and limit for your account.

```
GET https://whatanime.ga/api/me?token=your_api_token
```

| Fields        | Value             | Notes          |
| ------------- |-------------------| ---------------|
| token         | String (Optional) | Your API token |

Example Response

```json
{
  "user_id": 1001,
  "email": "soruly@gmail.com",
  "limit": 9,
  "limit_ttl": 45,
  "quota": 999,
  "quota_ttl": 86385,
  "user_limit": 10,
  "user_limit_ttl": 60,
  "user_quota": 1000,
  "user_quota_ttl": 86400
}
```

| Fields         | Meaning                                      | Value                               |
|----------------|----------------------------------------------|-------------------------------------|
| user_id        | Your Account ID                              | Number (null if no API token)       |
| email          | Your Account Email                           | String (IP address if no API token) |
| limit          | Current remaining limit for your account now | Number                              |
| limit_ttl      | Time until limit reset                       | Number (seconds)                    |
| quota          | Current remaining limit for your account now | Number                              |
| quota_ttl      | Time until quota reset                       | Number (seconds)                    |
| user_limit     | Rate limit associated with your account      | Number                              |
| user_limit_ttl | Usually set to 60 (1 minute)                 | Number (seconds)                    |
| user_quota     | Quota associated with your account           | Number                              |
| user_quota_ttl | Usually set to 86400 (1 day)                 | Number (seconds)                    |

## Search

Seach request should be POST as HTTP Form, not JSON

```
POST https://whatanime.ga/api/search?token=your_api_token
Content-Type: application/json

{
  "image" : "data:image/jpeg;base64,/9j/4AAQSkZJ......"
}
```

| Fields        | Value         | Notes  |
| ------------- |---------------| -------|
| image         | String (Required) | Base64 Encoded Image |
| filter        | Number (Optional) | Limit search to specific anilist ID. |

<p class="warning">
  Note that there is a hard limit of 1MB post size. You should ensure your Base64 encoded image is < 1MB. Otherwise the server responds with HTTP 413 (Request Entity Too Large).
</p>

Example
```javascript
fetch('/api/search', { 
  method: 'POST',
  body: JSON.stringify({image: searchImage.toDataURL('image/jpeg', 0.8)}),
  headers: { 'Content-Type': 'application/json' }
}).then(res=>res.json()).then(result=>{console.log(result)});
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
  "quota": 148,
  "quota_ttl": 85899,
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
| limit | Number of search limit remaining | Number |
| limit_ttl | Time until limit resets (seconds) | Number |
| quota | Number of search quota remaining | Number |
| quota_ttl | Time until quota resets (seconds) | Number |
| docs | Search results (see table below) | Array of Objects |


| Fields           | Meaning       | Value  |
| ---------------- |---------------| -------|
| from             | Starting time of the matching scene | Number (seconds, in 2 decimal places)
| to               | Ending time of the matching scene | Number (seconds, in 2 decimal places)
| at               | Exact time of the matching scene | Number (seconds, in 2 decimal places)
| episode          | The extracted episode number from filename | Number, "OVA/OAD", "Special", ""
| similarity       | Similarity compared to the search image | Number (float between 0-1)
| anilist_id       | The matching [AniList](https://anilist.co/) ID | Number
| mal_id           | The matching [MyAnimeList](https://myanimelist.net/) ID | Number or null
| is_adult         | Whether the anime is hentai | Boolean
| title_native     | Native (Japanese) title | String or null (Can be empty string)
| title_chinese    | Chinese title | String or null (Can be empty string)
| title_english    | English title | String or null (Can be empty string)
| title_romaji     | Title in romaji | String
| synonyms         | Alternate english titles | Array of String or []
| synonyms_chinese | Alternate chinese titles | Array of String or []
| filename         | The filename of file where the match is found | String
| tokenthumb       | A token for generating preview | String

<p class="tip">
  Search results with similarity lower than 85% are probably incorrect result (just similar, not a match). It's up to you to decide the cut-off value.
</p>

Notes:
- Results are always sorted by similarity, from most similar to least similar
- If multiple results are found in the same file, near the same timecode and has similarity > 98%, results are grouped as one, using `from` and `to` to indicate the starting time and ending time of that scene.
- `episode` is only an estimated number extraction from `filename`, it may fail and return empty string
- With `anilist_id`, you may get more anime info from `https://anilist.co/anime/{anilist_id}` . Read [AniList API](https://github.com/joshstar/AniList-API-Docs) for more information.
- The list of chinese translation can be obtained from [anilist-chinese](https://github.com/soruly/anilist-chinese)
- Search results would be cached for 5-60 minutes. Higher accuracy cache longer.


Aside from the JSON response or the `/me` endpoint, you can also get the current limit from HTTP response header.

Example
```
x-whatanime-limit: 9
x-whatanime-limit-ttl: 60
x-whatanime-quota: 148
x-whatanime-quota-ttl: 85899
```

Once the limit is reached. Server would respond HTTP 429 Too Many Requests, with a text message showing when the quota will reset.

Example
```
Search limit exceeded. Please wait 87 seconds.
```

<p class="tip">
  It is recommended to handle any exception cases properly in your application, such as rate limit, timeouts and network errors.
</p>


### Previews

With `tokenthumb`, you can access image preview of the matched scene. (not quite accurate due to timecode and seeking method)

`https://whatanime.ga/thumbnail.php?anilist_id=${anilist_id}&file=${encodeURIComponent(filename)}&t=${at}&token=${tokenthumb}`

There is a 3 second video preview of the matched scene. (1 seconds before and 2 seconds ahead)

`https://whatanime.ga/preview.php?anilist_id=${anilist_id}&file=${encodeURIComponent(filename)}&t=${at}&token=${tokenthumb}`
