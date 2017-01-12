<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Media;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Filters\Frame\FrameFilterInterface;
use FFMpeg\Filters\Frame\FrameFilters;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\FFProbe;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Coordinate\TimeCode;

class Frame extends AbstractMediaType
{
    /** @var TimeCode */
    private $timecode;
    /** @var Video */
    private $video;

    public function __construct(Video $video, FFMpegDriver $driver, FFProbe $ffprobe, TimeCode $timecode)
    {
        parent::__construct($video->getPathfile(), $driver, $ffprobe);
        $this->timecode = $timecode;
        $this->video = $video;
    }

    /**
     * Returns the video related to the frame.
     *
     * @return Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * {@inheritdoc}
     *
     * @return FrameFilters
     */
    public function filters()
    {
        return new FrameFilters($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return Frame
     */
    public function addFilter(FrameFilterInterface $filter)
    {
        $this->filters->add($filter);

        return $this;
    }

    /**
     * @return TimeCode
     */
    public function getTimeCode()
    {
        return $this->timecode;
    }

    /**
     * Saves the frame in the given filename.
     *
     * Uses the `unaccurate method by default.`
     *
     * @param string  $pathfile
     * @param Boolean $accurate
     *
     * @return Frame
     *
     * @throws RuntimeException
     */
    public function save($pathfile, $accurate = false)
    {
        /**
         * might be optimized with http://ffmpeg.org/trac/ffmpeg/wiki/Seeking%20with%20FFmpeg
         * @see http://ffmpeg.org/ffmpeg.html#Main-options
         */
        if (!$accurate) {
            $commands = array(
                '-y', '-ss', (string) $this->timecode,
                '-i', $this->pathfile,
                '-vframes', '1',
                '-f', 'image2'
            );
        } else {
            $commands = array(
                '-y', '-i', $this->pathfile,
                '-vframes', '1', '-ss', (string) $this->timecode,
                '-f', 'image2'
            );
        }

        foreach ($this->filters as $filter) {
            $commands = array_merge($commands, $filter->apply($this));
        }

        $commands = array_merge($commands, array($pathfile));

        try {
            $this->driver->command($commands);
        } catch (ExecutionFailureException $e) {
            $this->cleanupTemporaryFile($pathfile);
            throw new RuntimeException('Unable to save frame', $e->getCode(), $e);
        }

        return $this;
    }
}
