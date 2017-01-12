CHANGELOG
---------

* 0.6.1 (2016-03-08)

 * Support PHP 7 and test against
 * Unused code cleanup (@haphan)
 * Composer and tests cleanup (PSR-4 autoloading)
 * Allow usage of evenement v2.0

* 0.6.0 (2016-01-30)

 * AbstractData::get no longer throws exceptions (@sujayjaju).
 * Add crop filter (@cangelis).
 * Fix watermark (@sujayjaju).

* 0.5.1 (08-26-2014)

  * Fix video aspect ratio calculation (@nlegoff).

* 0.5.0 (08-12-2014)

  * Add support for Wav and AAC audio formats (@MrHash).
  * Add watermark filter (@sylvainv).
  * Add configuration for audio channels (@SimonSimCity).

* 0.4.4 (12-17-2013)

  * Fix width / height dimensions extraction.

* 0.4.3 (12-02-2013)

  * Fix using rotate and resize filters at the same time (#78)

* 0.4.2 (11-29-2013)

  * Add Rotate filter.
  * Remove time_start metadata when using synchronize filter
  * Remove restriction on filesystem resources.

* 0.4.1 (11-26-2013)

  * Add Clip filter (@guimeira)

* 0.4.0 (10-21-2013)

  * Add support for video to audio transcoding
  * BC Break : Add FormatInterface::getPasses and FormatInterface::getExtraParams

* 0.3.5 (10-21-2013)

  * Add vorbis audio format (@jacobbudin).
  * Fix #66 : Allow single pass encodings.

* 0.3.4 (09-05-2013)

  * Fix Invalid ratio computing.

* 0.3.3 (09-05-2013)

  * Add convenient Stream::getDimensions method to extract video dimension.
  * Add DisplayRatioFixer Frame filter.

* 0.3.2 (08-08-2013)

  * Fix A/V synchronization over flash and HTML5 players.

* 0.3.1 (08-06-2013)

  * Allow use of FFProbe on remote URIs.
  * Fix #47 : MediaTypeInterface::save adds filters depending on the codec.
  * Save frame to target file without prompt.

* 0.3.0 (07-04-2013)

  * Complete rewrite of the library, lots of BC breaks, check the doc.

* 0.2.4 (05-10-2013)

  * Add Video\ResizableInterface::getModulus method for better output scaling (@retrojunk)
  * Fix timeout setting on audio/video encoding (@xammep-ua)

* 0.2.3 (04-21-2013)

  * Add timeout getter and setter on FFMpeg and FFProbe
  * Add timeout setting via second argument on FFMpeg::load and FFProbe::load

* 0.2.2 (02-11-2013)

  * Add compatibility with FFMpeg 1.1
  * Upgrade deprecated options (`-ab`, `-qscale` and `-b`)
  * Use of a custom stat file for each multi-pass encoding (fix #20)
  * Use larger version range for dependencies

* 0.2.1 (02-04-2013)

  * Parse the output of FFProbe using correct EOL sequences (@ak76)
  * Add process timeout customization (@pulse00)
  * Fix `accurate` option (`FFMpeg::extractImage`)

* 0.2.0 (12-13-2012)

  * Add HelperInterface and support for realtime progress ( @pulse00 ).
  * Add `accurate` option to `FFMpeg::extractImage` method.

* 0.1.0 (10-30-2012)

  * First stable version.
