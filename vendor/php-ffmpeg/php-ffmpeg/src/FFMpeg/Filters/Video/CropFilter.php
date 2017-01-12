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
use FFMpeg\Coordinate\Point;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class CropFilter implements VideoFilterInterface
{
    /** @var integer */
    protected $priority;
    /** @var Dimension */
    protected $dimension;
    /** @var Point */
    protected $point;

    public function __construct(Point $point, Dimension $dimension, $priority = 0)
    {
        $this->priority = $priority;
        $this->dimension = $dimension;
        $this->point = $point;
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
        foreach ($video->getStreams()->videos() as $stream) {
            if ($stream->has('width') && $stream->has('height')) {
                $stream->set('width', $this->dimension->getWidth());
                $stream->set('height', $this->dimension->getHeight());
            }
        }

        return array(
            '-filter:v',
            'crop=' .
            $this->dimension->getWidth() .':' . $this->dimension->getHeight() . ':' . $this->point->getX() . ':' . $this->point->getY()
        );
    }
}
