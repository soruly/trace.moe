<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\Filters\Video\FrameRateFilter;
use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Coordinate\FrameRate;

class FrameRateFilterTest extends TestCase
{
    public function testApplyWithAFormatThatSupportsBFrames()
    {
        $framerate = new FrameRate(54);
        $gop = 42;

        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('supportBFrames')
            ->will($this->returnValue(true));

        $expected = array('-r', 54, '-b_strategy', '1', '-bf', '3', '-g', 42);

        $filter = new FrameRateFilter($framerate, $gop);
        $this->assertEquals($expected, $filter->apply($video, $format));
    }

    public function testApplyWithAFormatThatDoesNotSupportsBFrames()
    {
        $framerate = new FrameRate(54);
        $gop = 42;

        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('supportBFrames')
            ->will($this->returnValue(false));

        $expected = array('-r', 54);

        $filter = new FrameRateFilter($framerate, $gop);
        $this->assertEquals($expected, $filter->apply($video, $format));
    }
}
