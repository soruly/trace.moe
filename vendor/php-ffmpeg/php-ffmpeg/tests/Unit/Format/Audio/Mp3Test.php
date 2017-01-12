<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use FFMpeg\Format\Audio\Mp3;

class Mp3Test extends AudioTestCase
{
    public function getFormat()
    {
        return new Mp3();
    }
}
