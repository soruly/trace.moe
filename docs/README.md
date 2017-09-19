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

## List

Get a list of all indexed anime (for search filtering)

URL

```
GET /api/list?token={your_api_token} HTTP/1.1
Host: whatanime.ga
```

Example Response

```
[
  "1970-1989/City Hunter",
  "1970-1989/City Hunter 2",
  "1970-1989/City Hunter 3",
  "1970-1989/Gundam 0079",
  "1970-1989/Gundam Z"
]
```

The first part of the path is the year/season when the anime starts airing.

The second part of the path is anime name the server use internally. Ignore this if you can't read Chinese.

<p class="tip">
  Remember to always append wildcard * at the end of path.
</p>

## Search

```
POST /api/search?token={your_api_token} HTTP/1.1
Content-Type: application/x-www-form-urlencoded; charset=UTF-8
Host: whatanime.ga

image={Base64 Encoded Image}&filter=*
```

| Fields        | Value         | Notes  |
| ------------- |---------------| -------|
| image         | String (Required) | Base64 Encoded Image |
| filter        | String (Optional, defaults to \*) | Limit search in specific year / season, which works like searching in folders. You must add a wildcard at the end (e.g. 2017-\* , 2017-04/\*).  A complete list of paths can be obtained from the /list endpoint. |

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
  "RawDocsCount": [
    28103
  ],
  "RawDocsSearchTime": [
    134
  ],
  "ReRankSearchTime": [
    179
  ],
  "CacheHit": true,
  "trial": 4,
  "quota": 9,
  "expire": 278,
  "docs": [
    {
      "from": 706.833,
      "to": 708.667,
      "at": 708.667,
      "episode": 3,
      "similarity": 0.96267949,
      "title": "アルドノア・ゼロ",
      "anilist_id": 20632,
      "title_chinese": "ALDNOAH.ZERO",
      "synonyms_chinese": [
        "ALDNOAH ZERO"
      ],
      "title_english": "ALDNOAH.ZERO",
      "synonyms": [],
      "title_romaji": "ALDNOAH.ZERO",
      "season": "2014-07",
      "anime": "ALDNOAH.ZERO",
      "filename": "[KTXP][Aldnoah.Zero][03][BIG5][720p].mp4",
      "tokenthumb": "KXOniyuAe7Aa0mBslMA9ng"
    },
    {
      "from": 705.5,
      "to": 705.583,
      "at": 705.5,
      "episode": 3,
      "similarity": 0.920253086,
      "title": "アルドノア・ゼロ",
      "anilist_id": 20632,
      "title_chinese": "ALDNOAH.ZERO",
      "synonyms_chinese": [
        "ALDNOAH ZERO"
      ],
      "title_english": "ALDNOAH.ZERO",
      "synonyms": [],
      "title_romaji": "ALDNOAH.ZERO",
      "season": "2014-07",
      "anime": "ALDNOAH.ZERO",
      "filename": "[KTXP][Aldnoah.Zero][03][BIG5][720p].mp4",
      "tokenthumb": "-TfMF8eePyCcDEFVsURcDA"
    },
    {
      "from": 996.667,
      "to": 997,
      "at": 997,
      "episode": 14,
      "similarity": 0.86513167,
      "title": "新世界より",
      "anilist_id": 13125,
      "title_chinese": "來自新世界",
      "synonyms_chinese": [
        "自新世界"
      ],
      "title_english": "From the New World",
      "synonyms": [
        "Shin Sekai Yori"
      ],
      "title_romaji": "Shinsekai Yori",
      "season": "2012-10",
      "anime": "來自新世界",
      "filename": "[EMD][Shinsekai Yori][14][BIG5][X264_AAC][1280X720][FD1DF5EB].mp4",
      "tokenthumb": "wmDu4AgKG331bMnVUKAzUQ"
    },
    {
      "from": 1316.08,
      "to": 1316.08,
      "at": 1316.08,
      "episode": 22,
      "similarity": 0.85306277,
      "title": "ブリーチ",
      "anilist_id": 269,
      "title_chinese": "BLEACH",
      "synonyms_chinese": [
        "BLEACH 死神",
        "BLEACH 境·界",
        "BLEACH 漂靈"
      ],
      "title_english": "Bleach",
      "synonyms": [],
      "title_romaji": "Bleach",
      "season": "2004-10",
      "anime": "BLEACH",
      "filename": "022.mp4",
      "tokenthumb": "PltxiqyJRXyNTvABe6F0tg"
    }
  ]
}
```

| Fields        | Meaning       | Value  |
| ------------- |---------------| -------|
| RawDocsCount       | Number of frames compared for each trial | Array of Numbers |
| RawDocsSearchTime  | Time taken to retrieve the frames for each trial | Array of Numbers |
| ReRankSearchTime   | Time taken to compare the frames for each trial | Array of Numbers |
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
| title            | Japanese title | String
| title_chinese    | Chinese title | String
| title_english    | English title | String
| title_romaji     | Title in romaji | String
| synonyms         | Alternate english titles | Array of String or []
| synonyms_chinese | Alternate chinese titles | Array of String or []
| season           | The parent folder where the file is located | String (Movie, OVA, Others, Sukebei, 1970-1989, 1990-1999, 2000-01, 2017-01, etc)
| anime            | The folder where the file is located (This may act as a fallback when title is not found) | String
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

### Previews (experimental, sometimes not working)

With `tokenthumb`, you can access image preview of the matched scene. (not quite accurate due to timecode and seeking method)

`https://whatanime.ga/thumbnail.php?season=${season}&anime=${encodeURIComponent(anime)}&file=${encodeURIComponent(filename)}&t=${at}&token=${tokenthumb}`

There is a 3 second video preview of the matched scene. (1 seconds before and 2 seconds ahead)

`https://whatanime.ga/preview.php?season=${season}&anime=${encodeURIComponent(anime)}&file=${encodeURIComponent(filename)}&t=${at}&token=${tokenthumb}`
