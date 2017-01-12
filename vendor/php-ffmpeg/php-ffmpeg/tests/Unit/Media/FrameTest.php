<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\Frame;

class FrameTest extends AbstractMediaTestCase
{
    public function testGetTimeCode()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();

        $frame = new Frame($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode);
        $this->assertSame($timecode, $frame->getTimeCode());
    }

    public function testFiltersReturnFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();

        $frame = new Frame($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode);
        $this->assertInstanceOf('FFMpeg\Filters\Frame\FrameFilters', $frame->filters());
    }

    public function testAddFiltersAddsAFilter()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();

        $filters = $this->getMockBuilder('FFMpeg\Filters\FiltersCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $filter = $this->getMock('FFMpeg\Filters\Frame\FrameFilterInterface');

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $frame = new Frame($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode);
        $frame->setFiltersCollection($filters);
        $frame->addFilter($filter);
    }

    /**
     * @dataProvider provideSaveOptions
     */
    public function testSave($accurate, $commands)
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $timecode = $this->getTimeCodeMock();
        $timecode->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue('timecode'));

        $pathfile = '/target/destination';

        array_push($commands, $pathfile);

        $driver->expects($this->once())
            ->method('command')
            ->with($commands);

        $frame = new Frame($this->getVideoMock(__FILE__), $driver, $ffprobe, $timecode);
        $this->assertSame($frame, $frame->save($pathfile, $accurate));
    }

    public function provideSaveOptions()
    {
        return array(
            array(false, array(
                '-y', '-ss', 'timecode',
                '-i', __FILE__,
                '-vframes', '1',
                '-f', 'image2')
            ),
            array(true, array(
                '-y', '-i', __FILE__,
                '-vframes', '1', '-ss', 'timecode',
                '-f', 'image2'
            )),
        );
    }
}
