<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use FFMpeg\Format\Audio\Aac;

class AacTest extends AudioTestCase
{
    public function getFormat()
    {
        return new Aac();
    }
}
