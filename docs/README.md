# whatanime.ga API Docs

This API is still under development. Make sure you stay up-to-date with me and this project. 

## Getting Started
1. Request API token. Contact me via email(soruly@gmail.com) or [Twitter](https://twitter.com/soruly)(@soruly) or [Telegram](https://t.me/soruly)(@soruly).
2. Let me know what you are going to do with it. (Playing, experimenting and testing is fine)
3. Read the docs and try the API
4. Give feedbacks and suggestions

## Me

Let you verify that you have a valid user account.

URL

```
GET /api/me?token={your_api_token} HTTP/1.1
Host: whatanime.ga
```

Example Response

```
{
  "user_id": 1001,
  "email": "soruly@gmail.com"
}
```

Returns HTTP 403 if API token is invalid.

Returns HTTP 401 if API token is missing.

## Search

```
POST /api/search?token={your_api_token} HTTP/1.1
Content-Type: application/x-www-form-urlencoded; charset=UTF-8
Host: whatanime.ga

image={Base64 Encoded Image}
```

Note that there is a hard limit of 1MB post size. You should ensure your Base64 encoded image is < 1MB. Otherwise the server responds with HTTP 413 (Request Entity Too Large).

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
      "filename": "[KTXP][Aldnoah.Zero][03][BIG5][720p].mp4",
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
      "title_romaji": "ALDNOAH.ZERO"
    },
    {
      "from": 705.5,
      "to": 705.583,
      "at": 705.5,
      "filename": "[KTXP][Aldnoah.Zero][03][BIG5][720p].mp4",
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
      "title_romaji": "ALDNOAH.ZERO"
    },
    {
      "from": 996.667,
      "to": 997,
      "at": 997,
      "filename": "[EMD][Shinsekai Yori][14][BIG5][X264_AAC][1280X720][FD1DF5EB].mp4",
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
      "title_romaji": "Shinsekai Yori"
    },
    {
      "from": 1316.08,
      "to": 1316.08,
      "at": 1316.08,
      "filename": "022.mp4",
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
      "title_romaji": "Bleach"
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
| filename         | The filename of file where the match is found | String
| episode          | The extracted episode number from filename | Number, "OVA/OAD", "Special", ""
| similarity       | Similarity compared to the search image | Number (float between 0-1)
| anilist_id       | The matching [AniList](https://anilist.co/) ID | Number or null
| title            | Japanese title | String
| title_chinese    | Chinese title | String
| title_english    | English title | String
| title_romaji     | Title in romaji | String
| synonyms         | Alternate english titles | Array of String or []
| synonyms_chinese | Alternate chinese titles | Array of String or []

Notes:
- If multiple results are found in the same file, near the same timecode and has similarity > 98%, results are grouped as one, using `from` and `to` to indicate the starting time and ending time of that scene.
- `episode` is only an estimated number extraction from `filename`, it may fail and return empty string
- With `anilist_id`, you may get more anime info from `https://anilist.co/anime/{anilist_id}` . Read [AniList API](https://github.com/joshstar/AniList-API-Docs) for more information.
- The list of chinese translation can be obtained from [anilist-chinese](https://github.com/soruly/anilist-chinese)


### Search Quota

The `/api/search` endpoint has a request limit.

Each API token is limited to 3 searchs per minute. (subject to change, may increase in future)

Once the limit is reached. Server would respond HTTP 429 Too Many Requests, with a text message showing when the quota will reset.

Example
```
Search quota exceeded. Please wait 87 seconds.
```

Note that the server is served via cloudflare, their servers may have some rate limit control and anti-flood mechanism.
