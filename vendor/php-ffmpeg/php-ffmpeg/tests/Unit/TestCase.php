<?php

namespace Tests\FFMpeg\Unit;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function assertScalar($value)
    {
        $this->assertTrue(is_scalar($value));
    }

    public function getLoggerMock()
    {
        return $this->getMock('Psr\Log\LoggerInterface');
    }

    public function getCacheMock()
    {
        return $this->getMock('Doctrine\Common\Cache\Cache');
    }

    public function getTimeCodeMock()
    {
        return $this->getMockBuilder('FFMpeg\Coordinate\TimeCode')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getDimensionMock()
    {
        return $this->getMockBuilder('FFMpeg\Coordinate\Dimension')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFramerateMock()
    {
        return $this->getMockBuilder('FFMpeg\Coordinate\Framerate')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFrameMock()
    {
        return $this->getMockBuilder('FFMpeg\Media\Frame')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFFMpegDriverMock()
    {
        return $this->getMockBuilder('FFMpeg\Driver\FFMpegDriver')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFFProbeDriverMock()
    {
        return $this->getMockBuilder('FFMpeg\Driver\FFProbeDriver')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFFProbeMock()
    {
        return $this->getMockBuilder('FFMpeg\FFProbe')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getStreamMock()
    {
        return $this->getMockBuilder('FFMpeg\FFProbe\DataMapping\Stream')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getFFProbeParserMock()
    {
        return $this->getMock('FFMpeg\FFProbe\OutputParserInterface');
    }

    public function getFFProbeOptionsTesterMock()
    {
        return $this->getMock('FFMpeg\FFProbe\OptionsTesterInterface');
    }

    public function getFFProbeMapperMock()
    {
        return $this->getMock('FFMpeg\FFProbe\MapperInterface');
    }

    public function getFFProbeOptionsTesterMockWithOptions(array $options)
    {
        $tester = $this->getFFProbeOptionsTesterMock();

        $tester->expects($this->any())
            ->method('has')
            ->will($this->returnCallback(function ($option) use ($options) {
                return in_array($option, $options);
            }));

        return $tester;
    }

    public function getConfigurationMock()
    {
        return $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');
    }

    public function getFormatMock()
    {
        return $this->getMockBuilder('FFMpeg\FFProbe\DataMapping\Format')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getStreamCollectionMock()
    {
        return $this->getMockBuilder('FFMpeg\FFProbe\DataMapping\StreamCollection')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getAudioMock()
    {
        return $this->getMockBuilder('FFMpeg\Media\Audio')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getVideoMock($filename = null)
    {
        $video = $this->getMockBuilder('FFMpeg\Media\Video')
            ->disableOriginalConstructor()
            ->getMock();

        $video->expects($this->any())
            ->method('getPathfile')
            ->will($this->returnValue($filename));

        return $video;
    }
}
