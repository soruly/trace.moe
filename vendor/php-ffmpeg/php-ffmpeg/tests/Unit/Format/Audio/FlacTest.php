<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use FFMpeg\Format\Audio\Flac;

class FlacTest extends AudioTestCase
{
    public function getFormat()
    {
        return new Flac();
    }
}
