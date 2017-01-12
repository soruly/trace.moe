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

use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Media\Video;
use FFMpeg\Format\VideoInterface;

class FrameRateFilter implements VideoFilterInterface
{
    private $rate;
    private $gop;
    private $priority;

    public function __construct(FrameRate $rate, $gop, $priority = 0)
    {
        $this->rate = $rate;
        $this->gop = $gop;
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Returns the frame rate.
     *
     * @return FrameRate
     */
    public function getFrameRate()
    {
        return $this->rate;
    }

    /**
     * Returns the GOP size.
     *
     * @see https://wikipedia.org/wiki/Group_of_pictures
     *
     * @return Integer
     */
    public function getGOP()
    {
        return $this->gop;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Video $video, VideoInterface $format)
    {
        $commands = array('-r', $this->rate->getValue());

        /**
         * @see http://sites.google.com/site/linuxencoding/x264-ffmpeg-mapping
         */
        if ($format->supportBFrames()) {
            $commands[] = '-b_strategy';
            $commands[] = '1';
            $commands[] = '-bf';
            $commands[] = '3';
            $commands[] = '-g';
            $commands[] = $this->gop;
        }

        return $commands;
    }
}
