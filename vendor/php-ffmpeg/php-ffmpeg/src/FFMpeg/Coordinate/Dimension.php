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

/**
 * Dimension object, used for manipulating width and height couples
 */
class Dimension
{
    private $width;
    private $height;

    /**
     * @param integer $width
     * @param integer $height
     *
     * @throws InvalidArgumentException when one of the parameteres is invalid
     */
    public function __construct($width, $height)
    {
        if ($width <= 0 || $height <= 0) {
            throw new InvalidArgumentException('Width and height should be positive integer');
        }

        $this->width = (int) $width;
        $this->height = (int) $height;
    }

    /**
     * Returns width.
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Returns height.
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Returns the ratio.
     *
     * @param type $forceStandards Whether or not force the use of standards ratios;
     *
     * @return AspectRatio
     */
    public function getRatio($forceStandards = true)
    {
        return AspectRatio::create($this, $forceStandards);
    }
}
