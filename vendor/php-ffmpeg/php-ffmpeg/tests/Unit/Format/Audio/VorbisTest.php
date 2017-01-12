<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use FFMpeg\Format\Audio\Vorbis;

class VorbisTest extends AudioTestCase
{
    public function getFormat()
    {
        return new Vorbis();
    }
}
