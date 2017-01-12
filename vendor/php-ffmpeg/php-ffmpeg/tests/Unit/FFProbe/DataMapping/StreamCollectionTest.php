<?php

namespace Tests\FFMpeg\Unit\FFProbe\DataMapping;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\FFProbe\DataMapping\StreamCollection;

class StreamCollectionTest extends TestCase
{
    public function testAdd()
    {
        $stream = $this->getStreamMock();

        $collection = new StreamCollection();
        $this->assertEquals(array(), $collection->all());
        $collection->add($stream);
        $this->assertEquals(array($stream), $collection->all());
        $collection->add($stream);
        $this->assertEquals(array($stream, $stream), $collection->all());
    }

    public function testVideos()
    {
        $audio = $this->getStreamMock();
        $audio->expects($this->once())
            ->method('isVideo')
            ->will($this->returnValue(false));

        $video = $this->getStreamMock();
        $video->expects($this->once())
            ->method('isVideo')
            ->will($this->returnValue(true));

        $collection = new StreamCollection(array($audio, $video));
        $videos = $collection->videos();

        $this->assertInstanceOf('FFMpeg\FFProbe\DataMapping\StreamCollection', $videos);
        $this->assertCount(1, $videos);
        $this->assertEquals(array($video), $videos->all());
    }

    public function testAudios()
    {
        $audio = $this->getStreamMock();
        $audio->expects($this->once())
            ->method('isAudio')
            ->will($this->returnValue(true));

        $video = $this->getStreamMock();
        $video->expects($this->once())
            ->method('isAudio')
            ->will($this->returnValue(false));

        $collection = new StreamCollection(array($audio, $video));
        $audios = $collection->audios();

        $this->assertInstanceOf('FFMpeg\FFProbe\DataMapping\StreamCollection', $audios);
        $this->assertCount(1, $audios);
        $this->assertEquals(array($audio), $audios->all());
    }

    public function testCount()
    {
        $stream = $this->getStreamMock();

        $collection = new StreamCollection(array($stream));
        $this->assertCount(1, $collection);
    }

    public function testGetIterator()
    {
        $audio = $this->getStreamMock();
        $video = $this->getStreamMock();

        $collection = new StreamCollection(array($audio, $video));
        $this->assertInstanceOf('\Iterator', $collection->getIterator());
        $this->assertCount(2, $collection->getIterator());
    }

    public function testFirst()
    {
        $stream1 = $this->getStreamMock();
        $stream2 = $this->getStreamMock();

        $coll = new StreamCollection(array($stream1, $stream2));

        $this->assertSame($stream1, $coll->first());
    }
}
