<?php

namespace Tests\FFMpeg\Unit\Filters\Video;

use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\Filters\Video\RotateFilter;
use FFMpeg\Filters\Video\WatermarkFilter;
use Tests\FFMpeg\Unit\TestCase;

class WatermarkFilterTest extends TestCase
{
    public function testApplyWatermark()
    {
        $stream = new Stream(array('width' => 320, 'height' => 240, 'codec_type' => 'video'));
        $streams = new StreamCollection(array($stream));

        $video = $this->getVideoMock();

        $format = $this->getMock('FFMpeg\Format\VideoInterface');

        $filter = new WatermarkFilter(__DIR__ . '/../../../files/watermark.png');
        $this->assertEquals(array('-vf', 'movie='.__DIR__ .'/../../../files/watermark.png [watermark]; [in][watermark] overlay=0:0 [out]'), $filter->apply($video, $format));

        // check size of video is unchanged
        $this->assertEquals(320, $stream->get('width'));
        $this->assertEquals(240, $stream->get('height'));
    }

    public function testDifferentCoordinaates()
    {
        $video = $this->getVideoMock();
        $format = $this->getMock('FFMpeg\Format\VideoInterface');

        // test position absolute
        $filter = new WatermarkFilter(__DIR__ . '/../../../files/watermark.png', array(
            'position' => 'absolute',
            'x' => 10, 'y' => 5
        ));
        $this->assertEquals(array('-vf', 'movie='.__DIR__ .'/../../../files/watermark.png [watermark]; [in][watermark] overlay=10:5 [out]'), $filter->apply($video, $format));

        // test position relative
        $filter = new WatermarkFilter(__DIR__ . '/../../../files/watermark.png', array(
            'position' => 'relative',
            'bottom' => 10, 'left' => 5
        ));
        $this->assertEquals(array('-vf', 'movie='.__DIR__ .'/../../../files/watermark.png [watermark]; [in][watermark] overlay=5:main_h - 10 - overlay_h [out]'), $filter->apply($video, $format));

        // test position relative
        $filter = new WatermarkFilter(__DIR__ . '/../../../files/watermark.png', array(
            'position' => 'relative',
            'bottom' => 5, 'right' => 4
        ));
        $this->assertEquals(array('-vf', 'movie='.__DIR__ .'/../../../files/watermark.png [watermark]; [in][watermark] overlay=main_w - 4 - overlay_w:main_h - 5 - overlay_h [out]'), $filter->apply($video, $format));

        // test position relative
        $filter = new WatermarkFilter(__DIR__ . '/../../../files/watermark.png', array(
            'position' => 'relative',
            'left' => 5, 'top' => 11
        ));
        $this->assertEquals(array('-vf', 'movie='.__DIR__ .'/../../../files/watermark.png [watermark]; [in][watermark] overlay=5:11 [out]'), $filter->apply($video, $format));
    }
}
