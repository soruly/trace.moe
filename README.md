# trace.moe

[![License](https://img.shields.io/github/license/soruly/trace.moe.svg)](https://github.com/soruly/trace.moe/blob/master/LICENSE)
[![Discord](https://img.shields.io/discord/437578425767559188.svg)](https://discord.gg/K9jn6Kj)
[![Donate](https://img.shields.io/badge/donate-patreon-orange.svg)](https://www.patreon.com/soruly)

The website of trace.moe (whatanime.ga)

Image Reverse Search for Anime Scenes

Use anime screenshots to search where this scene is taken from.

It tells you which anime, which episode, and exactly which moment this scene appears in Japanese Anime.

## Demo

Demo image

![](https://images.plurk.com/2FKxneXP64qiKwjlUA7sKj.jpg)

Search result tells you which moment it appears.

![](https://addons.cdn.mozilla.net/user-media/previews/full/209/209947.png)

## How does it work

trace.moe uses [sola](https://github.com/soruly/sola) to index video and work with liresolr. This repo only include the webapp for trace.moe, which demonstrate how to integrate anilist info, and how thumbnail/video previews are generated. If you want to make your own video scene search engine, please refer to sola instead.

To learn more, read the presentation slides below

[Presentation slides](https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga.slide) May 2016

[Presentation slides](https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga-2017.slide) Jun 2017

[Presentation slides](https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga-2018.slide) Jun 2018

[Presentation slides](https://github.com/soruly/slides/blob/master/2019-COSCUP-trace.moe.md) Aug 2019

System Overview

![](https://pbs.twimg.com/media/CstZmrxUIAAi8La.jpg)

You may find some other related repo here

- [sola](https://github.com/soruly/sola)
- [LireSolr](https://github.com/soruly/liresolr)
- [anilist-crawler](https://github.com/soruly/anilist-crawler)
- [trace.moe-WebExtension](https://github.com/soruly/trace.moe-WebExtension)
- [trace.moe-telegram-bot](https://github.com/soruly/trace.moe-telegram-bot)

## Official API Docs (Beta)

https://soruly.github.io/trace.moe/

# CLI tools (3rd party)
what-anime-cli by Ilya Revenko https://github.com/irevenko/what-anime-cli

## Mobile Apps (3rd party)

WhatAnime by Andrée Torres
https://play.google.com/store/apps/details?id=com.maddog05.whatanime
Source: https://github.com/maddog05/whatanime-android

WhatAnime - 以图搜番 by Mystery0 (Simplified Chinese)
https://play.google.com/store/apps/details?id=pw.janyo.whatanime
Source: https://github.com/JanYoStudio/WhatAnime

## Integrating search with trace.moe

To add trace.moe as a search option for your site, pass the image URL via query string like this

```
https://trace.moe/?url=http://searchimageurl
```

You can also specify playback options like this

```
https://trace.moe/?autoplay=0&loop&mute=1&url=http://searchimageurl
```

Playback URL params:

| param    | value  | default (not set in URL param) | set with empty or other value |
| -------- | ------ | ------------------------------ | ----------------------------- |
| autoplay | 0 or 1 | 1                              | 1                             |
| mute     | 0 or 1 | 0                              | 1                             |
| loop     | 0 or 1 | 0                              | 1                             |

The `auto` URL parameter is no longer used, it would always search automatically when there is `?url=` param.

Note that the server cannot access private image URLs.
In that case, users has to copy and paste (Ctrl+V/Cmd+V) the image directly, or save and upload the file.
