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
- [LireSolr](https://github.com/soruly/liresolr) - image analysis and search plugin for Solr

Others:

- [anilist-crawler](https://github.com/soruly/anilist-crawler) - getting anilist info and store in mariaDB, now integrated into trace.moe-api
- [slides](https://github.com/soruly/slides) - past presentation slides on the project

## Hosting your own trace.moe system

You're going to need these docker images. They are provided in the `compose.yml` file.

| Parts                                                    | Docker CI Build                                                                                                                                                                             | Docker Image                                                                                                                                                                         |
| -------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| [liresolr](https://github.com/soruly/liresolr)           | [![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/soruly/liresolr/docker-image.yml?style=flat-square)](https://github.com/soruly/liresolr/actions)           | [![Docker Image Size](https://img.shields.io/docker/image-size/soruly/liresolr/latest?style=flat-square)](https://github.com/soruly/liresolr/pkgs/container/liresolr)                |
| [trace.moe-www](https://github.com/soruly/trace.moe-www) | [![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/soruly/trace.moe-www/docker-image.yml?style=flat-square)](https://github.com/soruly/trace.moe-www/actions) | [![Docker Image Size](https://img.shields.io/docker/image-size/soruly/trace.moe-www/latest?style=flat-square)](https://github.com/soruly/trace.moe-www/pkgs/container/trace.moe-www) |
| [trace.moe-api](https://github.com/soruly/trace.moe-api) | [![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/soruly/trace.moe-api/docker-image.yml?style=flat-square)](https://github.com/soruly/trace.moe-api/actions) | [![Docker Image Size](https://img.shields.io/docker/image-size/soruly/trace.moe-api/latest?style=flat-square)](https://github.com/soruly/trace.moe-api/pkgs/container/trace.moe-api) |

### Prerequisites

You need docker compose for your OS. Windows is supported via WSL2.

### Getting started

1. Copy `.env.example` to `.env` and update config as you need.

2. Ensure the directories exist before starting the containers. The `SOLR_DIR`, must have it's owner `uid` and `gid` set to `8983`.

```bash
mkdir -p /mnt/c/trace.moe/video/
mkdir -p /mnt/c/trace.moe/hash/
mkdir -p /mnt/c/trace.moe/sqlite/
mkdir -p /mnt/c/trace.moe/solr/
sudo chown 8983:8983 /mnt/c/trace.moe/solr/
```

3. Start the cluster

```bash
docker compose up
```

### How to begin hashing

trace.moe-api will scan the `VIDEO_PATH` every minute for new video files (.mp4 or .mkv). You can manually trigger a scan by calling `/scan` at the api server

```
curl http://localhost:3311/scan
```

Any video format readable by ffmpeg is supported. But the file extension must be either `.mp4` or `.mkv`, other files will be ignored.

### Folder structure for anilist ID

trace.moe assumes the folder name is anilist ID. If your data is not related to anilist ID, you can use any id/text you want. The system would still work partially without anilist data.
The files must be contained in 1-level folders, e.g.

```
/mnt/c/trace.moe/video/{anilist_ID}/foo.mp4
```
