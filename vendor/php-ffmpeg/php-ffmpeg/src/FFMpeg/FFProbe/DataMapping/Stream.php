<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe\DataMapping;

use FFMpeg\Exception\LogicException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\Coordinate\Dimension;

class Stream extends AbstractData
{
    /**
     * Returns true if the stream is an audio stream.
     *
     * @return Boolean
     */
    public function isAudio()
    {
        return $this->get('codec_type') === 'audio';
    }

    /**
     * Returns true if the stream is a video stream.
     *
     * @return Boolean
     */
    public function isVideo()
    {
        return $this->get('codec_type') === 'video';
    }

    /**
     * Returns the dimension of the video stream.
     *
     * @return Dimension
     *
     * @throws LogicException   In case the stream is not a video stream.
     * @throws RuntimeException In case the dimensions can not be extracted.
     */
    public function getDimensions()
    {
        if (!$this->isVideo()) {
            throw new LogicException('Dimensions can only be retrieved from video streams.');
        }

        $sampleRatio = $displayRatio = null;

        $width = $this->get('width');
        $height = $this->get('height');

        if (null !== $ratio = $this->extractRatio($this, 'sample_aspect_ratio')) {
            $sampleRatio = $ratio;
        }
        if (null !== $ratio = $this->extractRatio($this, 'display_aspect_ratio')) {
            $displayRatio = $ratio;
        }

        if (null === $height || null === $width) {
            throw new RuntimeException('Unable to extract dimensions.');
        }

        if (null !== $displayRatio && null !== $sampleRatio) {
            if ($sampleRatio[0] !== 1 && $sampleRatio[1] !== 1) {
                if (null !== $width && null !== $height) {
                    // stretch video according to pixel sample aspect ratio
                    $width = round($width * ($sampleRatio[0] / $sampleRatio[1]));
                    // set height according to display aspect ratio
                    $height = round($width * ($displayRatio[1] / $displayRatio[0]));
                }
            }
        }

        return new Dimension($width, $height);
    }

    /**
     * Extracts a ratio from a string in a \d+:\d+ format given a key name.
     *
     * @param  Stream     $stream The stream where to look for the ratio.
     * @param  string     $name   the name of the key.
     * @return null|array An array containing the width and the height, null if not found.
     */
    private function extractRatio(Stream $stream, $name)
    {
        if (!$stream->has($name)) {
            return;
        }

        $ratio = $stream->get($name);
        if (preg_match('/\d+:\d+/', $ratio)) {
            $data = array_filter(explode(':', $ratio), function ($int) {
                return $int > 0;
            });
            if (2 === count($data)) {
                return array_map(function ($int) { return (int) $int; }, $data);
            }
        }
    }
}
