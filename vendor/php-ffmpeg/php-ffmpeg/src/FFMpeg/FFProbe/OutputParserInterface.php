<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe;

interface OutputParserInterface
{
    /**
     * Parses ffprobe raw output.
     *
     * @param string $type One of FFProbe::TYPE_* constant
     * @param string $data The data
     *
     * @return array
     *
     * @throws InvalidArgumentException In case the type is not supported
     */
    public function parse($type, $data);
}
