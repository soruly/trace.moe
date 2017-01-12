<?php

namespace Tests\FFMpeg\Unit\Driver;

use Alchemy\BinaryDriver\Configuration;
use FFMpeg\Driver\FFMpegDriver;
use Tests\FFMpeg\Unit\TestCase;
use Symfony\Component\Process\ExecutableFinder;

class FFMpegDriverTest extends TestCase
{
    public function setUp()
    {
        $executableFinder = new ExecutableFinder();

        $found = false;
        foreach (array('avconv', 'ffmpeg') as $name) {
            if (null !== $executableFinder->find($name)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->markTestSkipped('Neither ffmpeg or avconv found');
        }
    }

    public function testCreate()
    {
        $logger = $this->getLoggerMock();
        $ffmpeg = FFMpegDriver::create($logger, array());
        $this->assertInstanceOf('FFMpeg\Driver\FFMpegDriver', $ffmpeg);
        $this->assertEquals($logger, $ffmpeg->getProcessRunner()->getLogger());
    }

    public function testCreateWithConfig()
    {
        $conf = new Configuration();
        $ffmpeg = FFMpegDriver::create($this->getLoggerMock(), $conf);
        $this->assertEquals($conf, $ffmpeg->getConfiguration());
    }

    /**
     * @expectedException FFMpeg\Exception\ExecutableNotFoundException
     */
    public function testCreateFailureThrowsAnException()
    {
        FFMpegDriver::create($this->getLoggerMock(), array('ffmpeg.binaries' => '/path/to/nowhere'));
    }
}
