<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\Point;
use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\Filters\Video\CropFilter;
use Tests\FFMpeg\Unit\TestCase;

class CropFilterTest extends TestCase
{

    public function testCommandParamsAreCorrectAndStreamIsUpdated()
    {
        $stream = new Stream(array('width' => 320, 'height' => 240, 'codec_type' => 'video'));
        $streams = new StreamCollection(array($stream));

        $video = $this->getVideoMock();
        $video->expects($this->once())
            ->method('getStreams')
            ->will($this->returnValue($streams));

        $format = $this->getMock('FFMpeg\Format\VideoInterface');

        $dimension = new Dimension(200, 150);
        $point = new Point(25, 35);
        $filter = new CropFilter($point, $dimension);
        $expected = array(
            '-filter:v',
            'crop=' . $dimension->getWidth() . ":" . $dimension->getHeight() . ":" . $point->getX() . ":" . $point->getY()
        );
        $this->assertEquals($expected, $filter->apply($video, $format));

        $this->assertEquals(200, $stream->get('width'));
        $this->assertEquals(150, $stream->get('height'));
    }

}
