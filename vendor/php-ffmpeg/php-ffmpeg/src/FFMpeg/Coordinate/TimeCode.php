<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Coordinate;

use FFMpeg\Exception\InvalidArgumentException;

class TimeCode
{
    //see http://www.dropframetimecode.org/
    private $hours;
    private $minutes;
    private $seconds;
    private $frames;

    public function __construct($hours, $minutes, $seconds, $frames)
    {
        $this->hours = $hours;
        $this->minutes = $minutes;
        $this->seconds = $seconds;
        $this->frames = $frames;
    }

    public function __toString()
    {
        return sprintf('%02d:%02d:%02d.%02d', $this->hours, $this->minutes, $this->seconds, $this->frames);
    }

    /**
     * Creates timecode from string.
     *
     * @param string $timecode
     *
     * @return TimeCode
     *
     * @throws InvalidArgumentException In case an invalid timecode is supplied
     */
    public static function fromString($timecode)
    {
        $days = 0;

        if (preg_match('/^[0-9]+:[0-9]+:[0-9]+:[0-9]+\.[0-9]+$/', $timecode)) {
            list($days, $hours, $minutes, $seconds, $frames) = sscanf($timecode, '%d:%d:%d:%d.%d');
        } elseif (preg_match('/^[0-9]+:[0-9]+:[0-9]+:[0-9]+:[0-9]+$/', $timecode)) {
            list($days, $hours, $minutes, $seconds, $frames) = sscanf($timecode, '%d:%d:%d:%d:%d');
        } elseif (preg_match('/^[0-9]+:[0-9]+:[0-9]+\.[0-9]+$/', $timecode)) {
            list($hours, $minutes, $seconds, $frames) = sscanf($timecode, '%d:%d:%d.%s');
        } elseif (preg_match('/^[0-9]+:[0-9]+:[0-9]+:[0-9]+$/', $timecode)) {
            list($hours, $minutes, $seconds, $frames) = sscanf($timecode, '%d:%d:%d:%s');
        } else {
            throw new InvalidArgumentException(sprintf('Unable to parse timecode %s', $timecode));
        }

        $hours += $days * 24;

        return new static($hours, $minutes, $seconds, $frames);
    }

    /**
     * Creates timecode from number of seconds.
     *
     * @param float $quantity
     *
     * @return TimeCode
     */
    public static function fromSeconds($quantity)
    {
        $minutes = $hours = $frames = 0;

        $frames = round(100 * ($quantity - floor($quantity)));
        $seconds = floor($quantity);

        if ($seconds > 59) {
            $minutes = floor($seconds / 60);
            $seconds = $seconds % 60;
        }
        if ($minutes > 59) {
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;
        }

        return new static($hours, $minutes, $seconds, $frames);
    }
}
