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

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class WatermarkFilter implements VideoFilterInterface
{
    /** @var string */
    private $watermarkPath;
    /** @var array */
    private $coordinates;
    /** @var integer */
    private $priority;

    public function __construct($watermarkPath, array $coordinates = array(), $priority = 0)
    {
        if (!file_exists($watermarkPath)) {
            throw new InvalidArgumentException(sprintf('File %s does not exist', $watermarkPath));
        }

        $this->watermarkPath = $watermarkPath;
        $this->coordinates = $coordinates;
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
     * {@inheritdoc}
     */
    public function apply(Video $video, VideoInterface $format)
    {
        $position = isset($this->coordinates['position']) ? $this->coordinates['position'] : 'absolute';

        switch ($position) {
            case 'relative':
                if (isset($this->coordinates['top'])) {
                    $y = $this->coordinates['top'];
                } elseif (isset($this->coordinates['bottom'])) {
                    $y = sprintf('main_h - %d - overlay_h', $this->coordinates['bottom']);
                } else {
                    $y = 0;
                }

                if (isset($this->coordinates['left'])) {
                    $x = $this->coordinates['left'];
                } elseif (isset($this->coordinates['right'])) {
                    $x = sprintf('main_w - %d - overlay_w', $this->coordinates['right']);
                } else {
                    $x = 0;
                }

                break;
            default:
                $x = isset($this->coordinates['x']) ? $this->coordinates['x'] : 0;
                $y = isset($this->coordinates['y']) ? $this->coordinates['y'] : 0;
                break;
        }

        return array('-vf', sprintf('movie=%s [watermark]; [in][watermark] overlay=%s:%s [out]', $this->watermarkPath, $x, $y));
    }
}
