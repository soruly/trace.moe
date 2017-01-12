<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use FFMpeg\Format\Audio\Wav;

class WavTest extends AudioTestCase
{
    public function getFormat()
    {
        return new Wav();
    }
}
