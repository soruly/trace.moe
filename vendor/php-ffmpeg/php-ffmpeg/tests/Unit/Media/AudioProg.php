<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\AudioInterface;

abstract class AudioProg implements ProgressableInterface, AudioInterface
{
}
