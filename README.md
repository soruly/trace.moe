# whatanime.ga

[![License](https://img.shields.io/github/license/soruly/whatanime.ga.svg?maxAge=2592000)](https://github.com/soruly/whatanime.ga/blob/master/LICENSE)
[![Join the chat at https://gitter.im/soruly/whatanime.ga](https://badges.gitter.im/soruly/whatanime.ga.svg)](https://gitter.im/soruly/whatanime.ga?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Donate](https://img.shields.io/badge/donate-patreon-orange.svg)](https://www.patreon.com/soruly)

The website of whatanime.ga

Image Reverse Search for Anime Scenes

Use anime screenshots to search where this scene is taken from.

It tells you which anime, which episode, and exactly which moment this scene appears in Japanese Anime.

## Demo

Demo image

![](https://images.plurk.com/2FKxneXP64qiKwjlUA7sKj.jpg)

Search result tells you which moment it appears.

![](https://addons.cdn.mozilla.net/user-media/previews/full/175/175674.png)

## How does it work

This repo for studying purpose only. Some settings and config are not included, it won't work out of the box after you clone this repo. Plase understand what you are doing before you fork this repo.

[Presentation slides](https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga.slide)

System Overview

![](https://pbs.twimg.com/media/CstZmrxUIAAi8La.jpg)

Some other components are also on GitHub

- [Shell Script and Python script for analyzing video for LireSolr](https://gist.github.com/soruly/032613e350cdbbe7b0dbe4a7f60bbefd)

- [Modified LireSolr search handler](https://gist.github.com/soruly/6d162ac7cc807e3ceb98)

- [Shell Script for checking video format](https://gist.github.com/soruly/1f8ec6f0a8772dfb59e49389bdde991f)

- [anilist-crawler](https://github.com/soruly/anilist-crawler)

## Official WebExtension
https://github.com/soruly/whatanime.ga-WebExtension

## Official Bot
https://github.com/soruly/whatanime.ga-telegram-bot

## Integrating search with whatanime.ga
To add whatanime.ga as a search option for your site, pass the image URL via query string like this
```
https://whatanime.ga/?url=http://searchimageurl
```
Note that the server cannot access private images via URL.
In that case, users has to copy and paste (Ctrl+V/Cmd+V) the image directly, or save and upload the file.

~~To begin search immediately after the image has loaded, you can add the `auto` option~~

The `auto` URL parameter is no longer used, it would always search automatically when there is `?url=` param.
