<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Video\WebM;

class WebMTest extends VideoTestCase
{
    public function getFormat()
    {
        return new WebM();
    }
}
