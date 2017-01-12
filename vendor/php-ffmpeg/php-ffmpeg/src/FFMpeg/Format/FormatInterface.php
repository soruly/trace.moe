<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FFMpeg\Format;

interface FormatInterface
{
    /**
     * Returns the number of passes.
     *
     * @return string
     */
    public function getPasses();

    /**
     * Returns an array of extra parameters to add to ffmpeg commandline.
     *
     * @return array()
     */
    public function getExtraParams();
}
