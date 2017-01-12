<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Video\X264;

class X264Test extends VideoTestCase
{
    public function getFormat()
    {
        return new X264();
    }
}
