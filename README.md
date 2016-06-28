# whatanime.ga
[![Website](https://img.shields.io/website-up-down-green-red/http/whatanime.ga.svg?maxAge=3600)](https://whatanime.ga/)
[![License](https://img.shields.io/github/license/soruly/whatanime.ga.svg?maxAge=2592000)](https://github.com/soruly/whatanime.ga/blob/master/LICENSE)

The website of whatanime.ga

Image Reverse Search for Anime Scenes

This repo for studying purpose only. Some settings and config are not included, it won't work out of the box after you clone this repo. Plase understand what you are doing before you fork this repo.

Please read the rest to understand how it works.

- [Overview of the system](https://go-talks.appspot.com/github.com/soruly/slides/whatanime.ga.slide)

- [Shell Script for analyzing video for LireSolr](https://gist.github.com/soruly/032613e350cdbbe7b0dbe4a7f60bbefd)

- [Modified LireSolr search handler](https://gist.github.com/soruly/6d162ac7cc807e3ceb98)

- [Shell Script for checking video format](https://gist.github.com/soruly/1f8ec6f0a8772dfb59e49389bdde991f)

## Integrating search with whatanime.ga
To add whatanime.ga as a search option for your site, pass the image URL via query string like this
```
https://whatanime.ga/?url=http://searchimageurl
```
Note that the server cannot access private images via URL.
In that case, users has to copy and paste (Ctrl+V/Cmd+V) the image directly, or save and upload the file.

To begin search immediately after the image has loaded, you can add the `auto` option like this
```
https://whatanime.ga/?auto&url=http://searchimageurl
```
Once the page is loaded, the `auto` parameter is removed from URL. Then it would automatically search once the image is loaded.

The intention of not searching automatically is because some users may need to crop / flip the image before searching.

There may be changes to these parameters, but the official Chrome Extension is always up-to-date.
https://github.com/soruly/whatanime.ga-Chrome-Extension
