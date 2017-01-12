<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Filters\Audio;

use FFMpeg\Format\AudioInterface;
use FFMpeg\Media\Audio;

class AudioResamplableFilter implements AudioFilterInterface
{
    /** @var string */
    private $rate;
    /** @var integer */
    private $priority;

    public function __construct($rate, $priority = 0)
    {
        $this->rate = $rate;
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
     *
     * @return Integer
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Audio $audio, AudioInterface $format)
    {
        return array('-ac', 2, '-ar', $this->rate);
    }
}
