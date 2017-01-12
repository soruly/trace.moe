<?php

namespace Tests\FFMpeg\Unit\Format\Video;

use FFMpeg\Format\Video\WMV;

class WMVTest extends VideoTestCase
{
    public function getFormat()
    {
        return new WMV();
    }
}
