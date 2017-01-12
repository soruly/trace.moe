<?php

namespace Tests\FFMpeg\Functional;

use FFMpeg\FFProbe;

class FFProbeTest extends FunctionalTestCase
{
    public function testProbeOnFile()
    {
        $ffprobe = FFProbe::create();
        $this->assertGreaterThan(0, count($ffprobe->streams(__DIR__ . '/../files/Audio.mp3')));
    }

    /**
     * @expectedException FFMpeg\Exception\RuntimeException
     */
    public function testProbeOnUnexistantFile()
    {
        $ffprobe = FFProbe::create();
        $ffprobe->streams('/path/to/no/file');
    }

    public function testProbeOnRemoteFile()
    {
        $ffprobe = FFProbe::create();
        $this->assertGreaterThan(0, count($ffprobe->streams('http://vjs.zencdn.net/v/oceans.mp4')));
    }
}
