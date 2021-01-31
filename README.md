# trace.moe

[![License](https://img.shields.io/github/license/soruly/trace.moe.svg)](https://github.com/soruly/trace.moe/blob/master/LICENSE)
[![Discord](https://img.shields.io/discord/437578425767559188.svg)](https://discord.gg/K9jn6Kj)
[![Donate](https://img.shields.io/badge/donate-patreon-orange.svg)](https://www.patreon.com/soruly)

Anime Scene Seach Engine

Trace back the scene where an anime screenshots is taken from.

It tells you which anime, which episode, and the exact moment this scene appears.

## Demo

Demo image

![](https://images.plurk.com/2FKxneXP64qiKwjlUA7sKj.jpg)

Search result tells you which moment it appears.

![](https://addons.cdn.mozilla.net/user-media/previews/full/209/209947.png)

## How does it work

This repo only include the webapp for trace.moe. To learn more, read the repos and presentation slides below

- [slides](https://github.com/soruly/slides) - presentation slides
- [sola](https://github.com/soruly/sola) - video indexing
- [LireSolr](https://github.com/soruly/liresolr) - image analysis and searching
- [anilist-crawler](https://github.com/soruly/anilist-crawler) - getting anilist info
- [trace.moe-media](https://github.com/soruly/trace.moe-media) - thumbnail / scene preview generation
- [trace.moe-WebExtension](https://github.com/soruly/trace.moe-WebExtension) - browser intergration
- [trace.moe-telegram-bot](https://github.com/soruly/trace.moe-telegram-bot) - official telegram bot

If you want to make your own video scene search engine, please refer to sola first.

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

Note that the server cannot access private image URLs.
In that case, users has to copy and paste (Ctrl+V/Cmd+V) the image directly, or save and upload the file.
