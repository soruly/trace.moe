<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Video;

use FFMpeg\Coordinate\Point;
use FFMpeg\Media\Video;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Filters\Audio\AudioResamplableFilter;
use FFMpeg\Filters\Audio\AudioFilters;

class VideoFilters extends AudioFilters
{
    public function __construct(Video $media)
    {
        parent::__construct($media);
    }

    /**
     * Resizes a video to a given dimension.
     *
     * @param Dimension $dimension
     * @param string    $mode
     * @param Boolean   $forceStandards
     *
     * @return VideoFilters
     */
    public function resize(Dimension $dimension, $mode = ResizeFilter::RESIZEMODE_FIT, $forceStandards = true)
    {
        $this->media->addFilter(new ResizeFilter($dimension, $mode, $forceStandards));

        return $this;
    }

    /**
     * Changes the video framerate.
     *
     * @param FrameRate $framerate
     * @param type      $gop
     *
     * @return VideoFilters
     */
    public function framerate(FrameRate $framerate, $gop)
    {
        $this->media->addFilter(new FrameRateFilter($framerate, $gop));

        return $this;
    }

    /**
     * Synchronizes audio and video.
     *
     * @return VideoFilters
     */
    public function synchronize()
    {
        $this->media->addFilter(new SynchronizeFilter());

        return $this;
    }

    /**
     * Clips (cuts) the video.
     *
     * @param TimeCode $start
     * @param TimeCode $duration
     *
     * @return VideoFilters
     */
    public function clip($start, $duration = null)
    {
        $this->media->addFilter(new ClipFilter($start, $duration));

        return $this;
    }

    /**
     * Resamples the audio file.
     *
     * @param Integer $rate
     *
     * @return AudioFilters
     */
    public function audioResample($rate)
    {
        $this->media->addFilter(new AudioResamplableFilter($rate));

        return $this;
    }

    public function rotate($angle)
    {
        $this->media->addFilter(new RotateFilter($angle, 30));

        return $this;
    }

    /**
     * Crops the video
     *
     * @param Point $point
     * @param Dimension $dimension
     *
     * @return VideoFilters
     */
    public function crop(Point $point, Dimension $dimension)
    {
        $this->media->addFilter(new CropFilter($point, $dimension));

        return $this;
    }

    /**
     * @param string $imagePath
     * @param array  $coordinates
     *
     * @return $this
     */
    public function watermark($imagePath, array $coordinates = array())
    {
        $this->media->addFilter(new WatermarkFilter($imagePath, $coordinates));

        return $this;
    }
}
