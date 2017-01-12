<?php

namespace Tests\FFMpeg\Unit\Format\Audio;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Format\Audio\DefaultAudio;

abstract class AudioTestCase extends TestCase
{
    public function testExtraParams()
    {
        foreach ($this->getFormat()->getExtraParams() as $param) {
            $this->assertScalar($param);
        }
    }

    public function testGetAudioCodec()
    {
        $this->assertScalar($this->getFormat()->getAudioCodec());
        $this->assertContains($this->getFormat()->getAudioCodec(), $this->getFormat()->getAvailableAudioCodecs());
    }

    public function testSetAudioCodec()
    {
        $format = $this->getFormat();

        foreach ($format->getAvailableAudioCodecs() as $codec) {
            $format->setAudioCodec($codec);
            $this->assertEquals($codec, $format->getAudioCodec());
        }
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testSetInvalidAudioCodec()
    {
        $this->getFormat()->setAudioCodec('invalid-random-audio-codec');
    }

    public function testGetAvailableAudioCodecs()
    {
        $this->assertGreaterThan(0, count($this->getFormat()->getAvailableAudioCodecs()));
    }

    public function testGetAudioKiloBitrate()
    {
        $this->assertInternalType('integer', $this->getFormat()->getAudioKiloBitrate());
    }

    public function testSetAudioKiloBitrate()
    {
        $format = $this->getFormat();
        $format->setAudioKiloBitrate(256);
        $this->assertEquals(256, $format->getAudioKiloBitrate());
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testSetInvalidKiloBitrate()
    {
        $this->getFormat()->setAudioKiloBitrate(0);
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testSetNegativeKiloBitrate()
    {
        $this->getFormat()->setAudioKiloBitrate(-10);
    }

    public function testGetAudioChannels()
    {
        $this->assertInternalType('null', $this->getFormat()->getAudioChannels());
    }

    public function testSetAudioChannels()
    {
        $format = $this->getFormat();
        $format->setAudioChannels(2);
        $this->assertEquals(2, $format->getAudioChannels());
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testSetInvalidChannels()
    {
        $this->getFormat()->setAudioChannels(0);
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testSetNegativeChannels()
    {
        $this->getFormat()->setAudioChannels(-10);
    }

    public function testCreateProgressListener()
    {
        $media = $this->getMock('FFMpeg\Media\MediaTypeInterface');
        $media->expects($this->any())
            ->method('getPathfile')
            ->will($this->returnValue(__FILE__));
        $format = $this->getFormat();
        $ffprobe = $this->getFFProbeMock();

        foreach ($format->createProgressListener($media, $ffprobe, 1, 3) as $listener) {
            $this->assertInstanceOf('FFMpeg\Format\ProgressListener\AudioProgressListener', $listener);
            $this->assertSame($ffprobe, $listener->getFFProbe());
            $this->assertSame(__FILE__, $listener->getPathfile());
            $this->assertSame(1, $listener->getCurrentPass());
            $this->assertSame(3, $listener->getTotalPass());
        }
    }

    /**
     * @return DefaultAudio
     */
    abstract public function getFormat();
}
