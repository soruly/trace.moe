<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Video;

/**
 * The X264 video format
 */
class X264 extends DefaultVideo
{
    /** @var boolean */
    private $bframesSupport = true;

    public function __construct($audioCodec = 'libfaac', $videoCodec = 'libx264')
    {
        $this
            ->setAudioCodec($audioCodec)
            ->setVideoCodec($videoCodec);
    }

    /**
     * {@inheritDoc}
     */
    public function supportBFrames()
    {
        return $this->bframesSupport;
    }

    /**
     * @param $support
     *
     * @return X264
     */
    public function setBFramesSupport($support)
    {
        $this->bframesSupport = $support;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableAudioCodecs()
    {
        return array('libvo_aacenc', 'libfaac', 'libmp3lame', 'libfdk_aac');
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableVideoCodecs()
    {
        return array('libx264');
    }

    /**
     * {@inheritDoc}
     */
    public function getPasses()
    {
        return 2;
    }

    /**
     * @return int
     */
    public function getModulus()
    {
        return 2;
    }
}
