<?php

namespace Tests\FFMpeg\Unit\Media;

use FFMpeg\Media\Video;
use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use FFMpeg\Format\VideoInterface;

class VideoTest extends AbstractStreamableTestCase
{
    public function testFiltersReturnsVideoFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $video = new Video(__FILE__, $driver, $ffprobe);
        $this->assertInstanceOf('FFMpeg\Filters\Video\VideoFilters', $video->filters());
    }

    public function testAddFiltersAddsAFilter()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $filters = $this->getMockBuilder('FFMpeg\Filters\FiltersCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->setFiltersCollection($filters);

        $filter = $this->getMock('FFMpeg\Filters\Video\VideoFilterInterface');

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $video->addFilter($filter);
    }

    public function testAddAudioFilterAddsAFilter()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $filters = $this->getMockBuilder('FFMpeg\Filters\FiltersCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->setFiltersCollection($filters);

        $filter = $this->getMock('FFMpeg\Filters\Audio\AudioFilterInterface');

        $filters->expects($this->once())
            ->method('add')
            ->with($filter);

        $video->addFilter($filter);
    }

    public function testFrameShouldReturnAFrame()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $at = $this->getTimeCodeMock();

        $video = new Video(__FILE__, $driver, $ffprobe);
        $frame = $video->frame($at);

        $this->assertInstanceOf('FFMpeg\Media\Frame', $frame);
        $this->assertSame($at, $frame->getTimeCode());
        $this->assertSame(__FILE__, $frame->getPathfile());
    }

    public function testSaveWithFailure()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $outputPathfile = '/target/file';
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(1));
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));

        $configuration = $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $failure = new ExecutionFailureException('failed to encode');
        $driver->expects($this->once())
            ->method('command')
            ->will($this->throwException($failure));

        $video = new Video(__FILE__, $driver, $ffprobe);
        $this->setExpectedException('FFMpeg\Exception\RuntimeException');
        $video->save($format, $outputPathfile);
    }

    public function testSaveAppliesFilters()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();
        $outputPathfile = '/target/file';
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));
        $format->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));

        $configuration = $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $video = new Video(__FILE__, $driver, $ffprobe);

        $filter = $this->getMock('FFMpeg\Filters\Video\VideoFilterInterface');
        $filter->expects($this->once())
            ->method('apply')
            ->with($video, $format)
            ->will($this->returnValue(array('extra-filter-command')));

        $capturedCommands = array();

        $driver->expects($this->exactly(2))
            ->method('command')
            ->with($this->isType('array'), false, $this->anything())
            ->will($this->returnCallback(function ($commands, $errors, $listeners) use (&$capturedCommands) {
                $capturedCommands[] = $commands;
            }));

        $video->addFilter($filter);
        $video->save($format, $outputPathfile);

        foreach ($capturedCommands as $commands) {
            $this->assertEquals('-y', $commands[0]);
            $this->assertEquals('-i', $commands[1]);
            $this->assertEquals(__FILE__, $commands[2]);
            $this->assertEquals('extra-filter-command', $commands[3]);
        }
    }

    /**
     * @dataProvider provideSaveData
     */
    public function testSaveShouldSave($threads, $expectedCommands, $expectedListeners, $format)
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $configuration = $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $configuration->expects($this->once())
            ->method('has')
            ->with($this->equalTo('ffmpeg.threads'))
            ->will($this->returnValue($threads));

        if ($threads) {
            $configuration->expects($this->once())
                ->method('get')
                ->with($this->equalTo('ffmpeg.threads'))
                ->will($this->returnValue(24));
        } else {
            $configuration->expects($this->never())
                ->method('get');
        }

        $capturedCommands = array();
        $capturedListeners = null;

        $driver->expects($this->exactly(count($expectedCommands)))
            ->method('command')
            ->with($this->isType('array'), false, $this->anything())
            ->will($this->returnCallback(function ($commands, $errors, $listeners) use (&$capturedCommands, &$capturedListeners) {
                $capturedCommands[] = $commands;
                $capturedListeners = $listeners;
            }));

        $outputPathfile = '/target/file';

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->save($format, $outputPathfile);

        foreach ($capturedCommands as $passKey => $pass) {
            $prefix = null;
            if (count($expectedCommands) > 1) {
                // look for pass commands only in multipass cases
                foreach ($pass as $command) {
                    $prefix = null;
                    if (false !== strpos($command, '/pass-')) {
                        $prefix = $command;
                        break;
                    }
                }

                if (null === $prefix) {
                    $this->fail('Unable to find pass prefix command.');
                }
            }

            $found = false || (null === $prefix);
            foreach ($pass as $key => $command) {
                if ($command === $prefix) {
                    $found = true;
                    unset($capturedCommands[$passKey][$key]);
                    $capturedCommands[$passKey] = array_values($capturedCommands[$passKey]);
                    break;
                }
            }

            if (!$found) {
                $this->fail('Unable to find pass prefix command back.');
            }
        }

        $this->assertEquals($expectedCommands, $capturedCommands);
        $this->assertEquals($expectedListeners, $capturedListeners);
    }

    public function provideSaveData()
    {
        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));
        $format->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(663));
        $format->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $format->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $format->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));

        $audioFormat = $this->getMock('FFMpeg\Format\AudioInterface');
        $audioFormat->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));
        $audioFormat->expects($this->any())
            ->method('getAudioCodec')
            ->will($this->returnValue('patati-patata-audio'));
        $audioFormat->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $audioFormat->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $audioFormat->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(1));

        $audioVideoFormat = $this->getMock('FFMpeg\Format\VideoInterface');
        $audioVideoFormat->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));
        $audioVideoFormat->expects($this->any())
            ->method('getVideoCodec')
            ->will($this->returnValue('gloubi-boulga-video'));
        $audioVideoFormat->expects($this->any())
            ->method('getAudioCodec')
            ->will($this->returnValue('patati-patata-audio'));
        $audioVideoFormat->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(664));
        $audioVideoFormat->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $audioVideoFormat->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $audioVideoFormat->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));

        $audioVideoFormatSinglePass = $this->getMock('FFMpeg\Format\VideoInterface');
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getVideoCodec')
            ->will($this->returnValue('gloubi-boulga-video'));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getAudioCodec')
            ->will($this->returnValue('patati-patata-audio'));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(664));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $audioVideoFormatSinglePass->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(1));

        $formatExtra = $this->getMock('FFMpeg\Format\VideoInterface');
        $formatExtra->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array('extra', 'param')));
        $formatExtra->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(665));
        $formatExtra->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $formatExtra->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $formatExtra->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));

        $listeners = array($this->getMock('Alchemy\BinaryDriver\Listeners\ListenerInterface'));

        $progressableFormat = $this->getMockBuilder('Tests\FFMpeg\Unit\Media\Prog')
            ->disableOriginalConstructor()->getMock();
        $progressableFormat->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));
        $progressableFormat->expects($this->any())
            ->method('createProgressListener')
            ->will($this->returnValue($listeners));
        $progressableFormat->expects($this->any())
            ->method('getKiloBitrate')
            ->will($this->returnValue(666));
        $progressableFormat->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $progressableFormat->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $progressableFormat->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));

        $progressableAudioFormat = $this->getMockBuilder('Tests\FFMpeg\Unit\Media\AudioProg')
            ->disableOriginalConstructor()->getMock();
        $progressableAudioFormat->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array()));
        $progressableAudioFormat->expects($this->any())
            ->method('getAudioCodec')
            ->will($this->returnValue('patati-patata-audio'));
        $progressableAudioFormat->expects($this->any())
            ->method('createProgressListener')
            ->will($this->returnValue($listeners));
        $progressableAudioFormat->expects($this->any())
            ->method('getAudioKiloBitrate')
            ->will($this->returnValue(92));
        $progressableAudioFormat->expects($this->any())
            ->method('getAudioChannels')
            ->will($this->returnValue(2));
        $progressableAudioFormat->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(1));

        return array(
            array(false, array(array(
                    '-y', '-i', __FILE__, '-b:v', '663k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                    '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    '-b:v', '663k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                    '/target/file',
                )), null, $format),
            array(false, array(array(
                    '-y', '-i', __FILE__,
                    '-vcodec', 'gloubi-boulga-video',
                    '-acodec', 'patati-patata-audio', '-b:v', '664k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                    '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    '-vcodec', 'gloubi-boulga-video',
                    '-acodec', 'patati-patata-audio',
                    '-b:v', '664k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                    '/target/file',
                )), null, $audioVideoFormat),
            array(false, array(array(
                    '-y', '-i', __FILE__,
                    '-vcodec', 'gloubi-boulga-video',
                    '-acodec', 'patati-patata-audio', '-b:v', '664k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2',
                    '/target/file',
                )), null, $audioVideoFormatSinglePass),
            array(false, array(array(
                    '-y', '-i', __FILE__,
                    'extra', 'param','-b:v', '665k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                    '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    'extra', 'param', '-b:v', '665k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                    '/target/file',
                )), null, $formatExtra),
            array(true, array(array(
                    '-y', '-i', __FILE__,
                    '-threads', 24, '-b:v', '663k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                    '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    '-threads', 24,
                    '-b:v', '663k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                    '/target/file',
                )), null, $format),
            array(true, array(array(
                    '-y', '-i', __FILE__,
                    'extra', 'param', '-threads', 24, '-b:v', '665k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                    '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    'extra', 'param', '-threads', 24, '-b:v', '665k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                    '/target/file',
                )), null, $formatExtra),
            array(false, array(array(
                    '-y', '-i', __FILE__, '-b:v', '666k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                    '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    '-b:v', '666k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                    '/target/file',
                )), $listeners, $progressableFormat),
            array(true, array(array(
                    '-y', '-i', __FILE__,
                    '-threads', 24, '-b:v', '666k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '1', '-passlogfile',
                    '/target/file',
                ), array(
                    '-y', '-i', __FILE__,
                    '-threads', 24,
                    '-b:v', '666k',
                    '-refs', '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
                    '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71', '-qcomp', '0.6',
                    '-qdiff', '4', '-trellis', '1', '-b:a', '92k', '-ac', '2', '-pass', '2', '-passlogfile',
                    '/target/file',
                )), $listeners, $progressableFormat),
            array(true, array(array(
                    '-y', '-i', __FILE__,
                    '-threads', 24, '-acodec', 'patati-patata-audio',
                    '-b:a', '92k', '-ac', '2',
                    '/target/file',
                )), null, $audioFormat),
            array(true, array(array(
                    '-y', '-i', __FILE__,
                    '-threads', 24, '-acodec', 'patati-patata-audio',
                    '-b:a', '92k', '-ac', '2',
                    '/target/file',
                )), $listeners, $progressableAudioFormat),
        );
    }

    public function testSaveShouldNotStoreCodecFiltersInTheMedia()
    {
        $driver = $this->getFFMpegDriverMock();
        $ffprobe = $this->getFFProbeMock();

        $configuration = $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');

        $driver->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $configuration->expects($this->any())
            ->method('has')
            ->with($this->equalTo('ffmpeg.threads'))
            ->will($this->returnValue(true));

        $configuration->expects($this->any())
            ->method('get')
            ->with($this->equalTo('ffmpeg.threads'))
            ->will($this->returnValue(24));

        $capturedCommands = array();

        $driver->expects($this->exactly(4))
            ->method('command')
            ->with($this->isType('array'), false, $this->anything())
            ->will($this->returnCallback(function ($commands, $errors, $listeners) use (&$capturedCommands, &$capturedListeners) {
                $capturedCommands[] = $commands;
            }));

        $outputPathfile = '/target/file';

        $format = $this->getMock('FFMpeg\Format\VideoInterface');
        $format->expects($this->any())
            ->method('getExtraParams')
            ->will($this->returnValue(array('param')));
        $format->expects($this->any())
            ->method('getPasses')
            ->will($this->returnValue(2));

        $video = new Video(__FILE__, $driver, $ffprobe);
        $video->save($format, $outputPathfile);
        $video->save($format, $outputPathfile);

        $expectedPass1 = array(
            '-y', '-i', __FILE__, 'param', '-threads', 24, '-b:v', 'k', '-refs',
            '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
            '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71',
            '-qcomp', '0.6', '-qdiff', '4', '-trellis', '1',
            '-pass', '1', '-passlogfile', '/target/file',
        );
        $expectedPass2 = array(
            '-y', '-i', __FILE__, 'param', '-threads', 24, '-b:v', 'k', '-refs',
            '6', '-coder', '1', '-sc_threshold', '40', '-flags', '+loop',
            '-me_range', '16', '-subq', '7', '-i_qfactor', '0.71',
            '-qcomp', '0.6', '-qdiff', '4', '-trellis', '1',
            '-pass', '2', '-passlogfile', '/target/file',
        );

        $n = 1;
        foreach ($capturedCommands as $capturedCommand) {
            $prefix = null;
            foreach ($capturedCommand as $command) {
                if (false !== strpos($command, '/pass-')) {
                    $prefix = $command;
                    break;
                }
            }

            if (null === $prefix) {
                $this->fail('Unable to find pass prefix command.');
            }

            $found = false;
            foreach ($capturedCommand as $key => $command) {
                if ($command === $prefix) {
                    $found = true;
                    unset($capturedCommand[$key]);
                    $capturedCommand = array_values($capturedCommand);
                    break;
                }
            }

            if (!$found) {
                $this->fail('Unable to find pass prefix command back.');
            }

            if (0 === $n % 2) {
                $this->assertEquals($expectedPass2, $capturedCommand);
            } else {
                $this->assertEquals($expectedPass1, $capturedCommand);
            }
            $n++;
        }
    }

    public function getClassName()
    {
        return 'FFMpeg\Media\Video';
    }
}
