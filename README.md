# trace.moe

[![License](https://img.shields.io/github/license/soruly/trace.moe.svg?style=flat-square)](https://github.com/soruly/trace.moe/blob/master/LICENSE)
[![Discord](https://img.shields.io/discord/437578425767559188.svg?style=flat-square)](https://discord.gg/K9jn6Kj)

Anime Scene Search Engine

Trace back the scene where an anime screenshots is taken from.

It tells you which anime, which episode, and the exact moment this scene appears.

![](https://images.plurk.com/4YFSKDO1Yc5Fbj6yj4ot2.jpg)

Try this image yourself.

![](https://images.plurk.com/32B15UXxymfSMwKGTObY5e.jpg)

## Web Integrations

Link to trace.moe from other websites, you can pass image URL in query string like this:

```
https://trace.moe/?url=https://images.plurk.com/32B15UXxymfSMwKGTObY5e.jpg
```

## trace.moe API

For Bots/Apps, refer to https://soruly.github.io/trace.moe-api/

## System Overview

This repo is just an index page for the whole trace.moe system. It consists of different parts as below:

Client-side:

- [trace.moe-www](https://github.com/soruly/trace.moe-www) - web server serving the webpage [trace.moe](https://trace.moe)
- [trace.moe-WebExtension](https://github.com/soruly/trace.moe-WebExtension) - browser add-ons to help copying and pasting images
- [trace.moe-telegram-bot](https://github.com/soruly/trace.moe-telegram-bot) - official Telegram Bot

Server-side:

- [trace.moe-api](https://github.com/soruly/trace.moe-api) - API server for image search and database updates
- [trace.moe-media](https://github.com/soruly/trace.moe-media) - media server for video storage and scene preview generation, now integrated into trace.moe-api
- [trace.moe-worker](https://github.com/soruly/trace.moe-worker) - includes hasher, loader and watcher, now integrated into trace.moe-api
- [LireSolr](https://github.com/soruly/liresolr) - image analysis and search plugin for Solr, now integrated into trace.moe-api

Others:

- [anilist-crawler](https://github.com/soruly/anilist-crawler) - getting anilist info and store in mariaDB, now integrated into trace.moe-api
- [slides](https://github.com/soruly/slides) - past presentation slides on the project

## Hosting your own trace.moe system

You're going to need these docker images. They are provided in the `compose.yml` file.

| Parts                                                    | Docker CI Build                                                                                                                                                                             | Docker Image                                                                                                                                                                         |
| -------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| [trace.moe-www](https://github.com/soruly/trace.moe-www) | [![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/soruly/trace.moe-www/docker-image.yml?style=flat-square)](https://github.com/soruly/trace.moe-www/actions) | [![Docker Image Size](https://img.shields.io/docker/image-size/soruly/trace.moe-www/latest?style=flat-square)](https://github.com/soruly/trace.moe-www/pkgs/container/trace.moe-www) |
| [trace.moe-api](https://github.com/soruly/trace.moe-api) | [![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/soruly/trace.moe-api/docker-image.yml?style=flat-square)](https://github.com/soruly/trace.moe-api/actions) | [![Docker Image Size](https://img.shields.io/docker/image-size/soruly/trace.moe-api/latest?style=flat-square)](https://github.com/soruly/trace.moe-api/pkgs/container/trace.moe-api) |

### Prerequisites

You need docker compose for your OS. Windows is supported via WSL2.

### Getting started

1. Create directory `/mnt/c/trace.moe/video/`

2. Create subdirectory using anilist ID as folder name.

3. Put video files in it. The full path should look like this `/mnt/c/trace.moe/video/{anilist_ID}/foo.mp4`

4. Copy `.env.example` to `.env` and set `VIDEO_PATH` to `/mnt/c/trace.moe/video/`

5. Start the containers `docker compose up -d`

It will scan the `VIDEO_PATH` every minute for new video files (.mp4 .mkv .webm files only, others are ignored).

You can check the indexing process the from logs of api server

6. Open http://localhost:3000 and start searching

### More configurations

Other configurations like port mapping are defined in compose.yml

You can also increase `MAX_WORKER` to make hashing faster.
