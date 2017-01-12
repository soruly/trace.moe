<?php

namespace Tests\FFMpeg\Unit;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use FFMpeg\FFProbe\DataMapping\Stream;

class FFMpegTest extends TestCase
{
    /**
     * @expectedException \FFMpeg\Exception\RuntimeException
     * @expectedExceptionMessage Unable to probe "/path/to/unknown/file".
     */
    public function testOpenInvalid()
    {
        $ffmpeg = new FFMpeg($this->getFFMpegDriverMock(), $this->getFFProbeMock());
        $ffmpeg->open('/path/to/unknown/file');
    }

    public function testOpenAudio()
    {
        $streams = $this->getStreamCollectionMock();
        $streams->expects($this->once())
            ->method('audios')
            ->will($this->returnValue(new StreamCollection(array(new Stream(array())))));
        $streams->expects($this->once())
            ->method('videos')
            ->will($this->returnValue(array()));

        $ffprobe = $this->getFFProbeMock();
        $ffprobe->expects($this->once())
            ->method('streams')
            ->with(__FILE__)
            ->will($this->returnValue($streams));

        $ffmpeg = new FFMpeg($this->getFFMpegDriverMock(), $ffprobe);
        $this->assertInstanceOf('FFMpeg\Media\Audio', $ffmpeg->open(__FILE__));
    }

    public function testOpenVideo()
    {
        $streams = $this->getStreamCollectionMock();
        $streams->expects($this->once())
            ->method('videos')
            ->will($this->returnValue(new StreamCollection(array(new Stream(array())))));
        $streams->expects($this->never())
            ->method('audios');

        $ffprobe = $this->getFFProbeMock();
        $ffprobe->expects($this->once())
            ->method('streams')
            ->with(__FILE__)
            ->will($this->returnValue($streams));

        $ffmpeg = new FFMpeg($this->getFFMpegDriverMock(), $ffprobe);
        $this->assertInstanceOf('FFMpeg\Media\Video', $ffmpeg->open(__FILE__));
    }

    /**
     * @expectedException \FFMpeg\Exception\InvalidArgumentException
     */
    public function testOpenUnknown()
    {
        $ffprobe = $this->getFFProbeMock();
        $ffprobe->expects($this->once())
            ->method('streams')
            ->with(__FILE__)
            ->will($this->returnValue(new StreamCollection()));

        $ffmpeg = new FFMpeg($this->getFFMpegDriverMock(), $ffprobe);
        $ffmpeg->open(__FILE__);
    }

    public function testCreateWithoutLoggerOrProbe()
    {
        $this->assertInstanceOf('FFMpeg\FFMpeg', FFMpeg::create());
    }

    public function testCreateWithLoggerAndProbe()
    {
        $logger = $this->getLoggerMock();
        $ffprobe = $this->getFFProbeMock();

        $ffmpeg = FFMpeg::create(array('timeout' => 42), $logger, $ffprobe);
        $this->assertInstanceOf('FFMpeg\FFMpeg', $ffmpeg);

        $this->assertSame($logger, $ffmpeg->getFFMpegDriver()->getProcessRunner()->getLogger());
        $this->assertSame($ffprobe, $ffmpeg->getFFProbe());
        $this->assertSame(42, $ffmpeg->getFFMpegDriver()->getProcessBuilderFactory()->getTimeout());
    }

    public function testGetSetFFProbe()
    {
        $ffprobe = $this->getFFProbeMock();
        $ffmpeg = new FFMpeg($this->getFFMpegDriverMock(), $ffprobe);
        $this->assertSame($ffprobe, $ffmpeg->getFFProbe());
        $anotherFFProbe = $this->getFFProbeMock();
        $ffmpeg->setFFProbe($anotherFFProbe);
        $this->assertSame($anotherFFProbe, $ffmpeg->getFFProbe());
    }

    public function testGetSetDriver()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffmpeg = new FFMpeg($driver, $this->getFFProbeMock());
        $this->assertSame($driver, $ffmpeg->getFFMpegDriver());
        $anotherDriver = $this->getFFMpegDriverMock();
        $ffmpeg->setFFMpegDriver($anotherDriver);
        $this->assertSame($anotherDriver, $ffmpeg->getFFMpegDriver());
    }
}
