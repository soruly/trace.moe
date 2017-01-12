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

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Media\Video;
use FFMpeg\Format\VideoInterface;

class RotateFilter implements VideoFilterInterface
{
    const ROTATE_90 = 'transpose=1';
    const ROTATE_180 = 'hflip,vflip';
    const ROTATE_270 = 'transpose=2';

    /** @var string */
    private $angle;
    /** @var integer */
    private $priority;

    public function __construct($angle, $priority = 0)
    {
        $this->setAngle($angle);
        $this->priority = (int) $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return Dimension
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Video $video, VideoInterface $format)
    {
        if (in_array($this->angle, array(self::ROTATE_90, self::ROTATE_270), true)) {
            foreach ($video->getStreams()->videos() as $stream) {
                if ($stream->has('width') && $stream->has('height')) {
                    $width = $stream->get('width');
                    $stream->set('width', $stream->get('height'));
                    $stream->set('height', $width);
                }
            }
        }

        return array('-vf', $this->angle, '-metadata:s:v:0', 'rotate=0');
    }

    private function setAngle($angle)
    {
        switch ($angle) {
            case self::ROTATE_90:
            case self::ROTATE_180:
            case self::ROTATE_270:
                $this->angle = $angle;
                break;
            default:
                throw new InvalidArgumentException('Invalid angle value.');
        }
    }
}
