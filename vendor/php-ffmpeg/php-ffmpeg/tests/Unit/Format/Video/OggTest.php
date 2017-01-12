<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Video\Ogg;

class OggTest extends VideoTestCase
{
    public function getFormat()
    {
        return new Ogg();
    }
}
