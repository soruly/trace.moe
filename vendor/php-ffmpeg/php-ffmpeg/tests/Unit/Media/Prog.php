<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Format\ProgressableInterface;
use FFMpeg\Format\VideoInterface;

abstract class Prog implements ProgressableInterface, VideoInterface
{
}
