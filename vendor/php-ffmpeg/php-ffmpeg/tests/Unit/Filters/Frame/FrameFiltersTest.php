<?php

namespace Tests\FFMpeg\Unit\Filters\Frame;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Filters\Frame\FrameFilters;

class FrameFiltersTest extends TestCase
{
    public function testResize()
    {
        $frame = $this->getFrameMock();
        $filters = new FrameFilters($frame);

        $frame->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf('FFMpeg\Filters\Frame\DisplayRatioFixerFilter'));

        $filters->fixDisplayRatio();
    }
}
