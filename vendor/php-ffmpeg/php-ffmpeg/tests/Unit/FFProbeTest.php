<?php

namespace Tests\FFMpeg\Unit;

use FFMpeg\FFProbe;
use Symfony\Component\Process\ExecutableFinder;
use Alchemy\BinaryDriver\ConfigurationInterface;
use Alchemy\BinaryDriver\Configuration;

class FFProbeTest extends TestCase
{
    public function testGetSetParser()
    {
        $ffprobe = new FFProbe($this->getFFProbeDriverMock(), $this->getCacheMock());
        $parser = $this->getFFProbeParserMock();

        $ffprobe->setParser($parser);
        $this->assertSame($parser, $ffprobe->getParser());
    }

    public function testGetSetFFProbeDriver()
    {
        $ffprobe = new FFProbe($this->getFFProbeDriverMock(), $this->getCacheMock());
        $driver = $this->getFFProbeDriverMock();

        $ffprobe->setFFProbeDriver($driver);
        $this->assertSame($driver, $ffprobe->getFFProbeDriver());
    }

    public function testGetSetFFProbeMapper()
    {
        $ffprobe = new FFProbe($this->getFFProbeDriverMock(), $this->getCacheMock());
        $mapper = $this->getFFProbeMapperMock();

        $ffprobe->setMapper($mapper);
        $this->assertSame($mapper, $ffprobe->getMapper());
    }

    public function testGetSetOptionsTester()
    {
        $ffprobe = new FFProbe($this->getFFProbeDriverMock(), $this->getCacheMock());
        $tester = $this->getFFProbeOptionsTesterMock();

        $ffprobe->setOptionsTester($tester);
        $this->assertSame($tester, $ffprobe->getOptionsTester());
    }

    public function testGetSetCache()
    {
        $ffprobe = new FFProbe($this->getFFProbeDriverMock(), $this->getCacheMock());
        $cache = $this->getCacheMock();

        $ffprobe->setCache($cache);
        $this->assertSame($cache, $ffprobe->getCache());
    }

    public function provideDataWhitoutCache()
    {
        $stream = $this->getStreamMock();
        $format = $this->getFormatMock();

        return array(
            array($stream, 'streams', array('-show_streams', '-print_format'), FFProbe::TYPE_STREAMS, array(__FILE__, '-show_streams', '-print_format', 'json'), false),
            array($format, 'format', array('-show_format', '-print_format'), FFProbe::TYPE_FORMAT, array(__FILE__, '-show_format', '-print_format', 'json'), false),
            array($stream, 'streams', array('-show_streams'), FFProbe::TYPE_STREAMS, array(__FILE__, '-show_streams'), true),
            array($format, 'format', array('-show_format'), FFProbe::TYPE_FORMAT, array(__FILE__, '-show_format'), true),
        );
    }

    /**
     * @dataProvider provideDataWhitoutCache
     */
    public function testProbeWithoutCache($output, $method, $commands, $type, $caughtCommands, $isRaw)
    {
        $pathfile = __FILE__;
        $data = array('key' => 'value');
        $rawData = 'raw data';

        $ffprobe = new FFProbe($this->getFFProbeDriverMock(), $this->getCacheMock());

        $mapper = $this->getFFProbeMapperMock();
        $mapper->expects($this->once())
            ->method('map')
            ->with($type, $data)
            ->will($this->returnValue($output));

        $parser = $this->getFFProbeParserMock();

        if ($isRaw) {
            $parser->expects($this->once())
                ->method('parse')
                ->with($type, $rawData)
                ->will($this->returnValue($data));
        } else {
            $parser->expects($this->never())
                ->method('parse');
        }

        $tester = $this->getFFProbeOptionsTesterMockWithOptions($commands);

        $cache = $this->getCacheMock();
        $cache->expects($this->once())
            ->method('contains')
            ->will($this->returnValue(false));
        $cache->expects($this->never())
            ->method('fetch');
        $cache->expects($this->once())
            ->method('save')
            ->with($this->anything(), $output);

        $driver = $this->getFFProbeDriverMock();
        $driver->expects($this->once())
            ->method('command')
            ->with($caughtCommands)
            ->will($this->returnValue($isRaw ? $rawData : json_encode($data)));

        $ffprobe->setOptionsTester($tester)
            ->setCache($cache)
            ->setMapper($mapper)
            ->setFFProbeDriver($driver)
            ->setParser($parser);

        $this->assertEquals($output, call_user_func(array($ffprobe, $method), $pathfile));
    }

    public function provideDataForInvalidJson()
    {
        $stream = $this->getStreamMock();
        $format = $this->getFormatMock();

        return array(
            array($stream, 'streams', array('-show_streams', '-print_format'), FFProbe::TYPE_STREAMS, array(__FILE__, '-show_streams', '-print_format', 'json')),
            array($format, 'format', array('-show_format', '-print_format'), FFProbe::TYPE_FORMAT, array(__FILE__, '-show_format', '-print_format', 'json')),
        );
    }

    /**
     * @dataProvider provideDataForInvalidJson
     */
    public function testProbeWithWrongJson($output, $method, $commands, $type, $caughtCommands)
    {
        $pathfile = __FILE__;
        $data = array('key' => 'value');

        $ffprobe = new FFProbe($this->getFFProbeDriverMock(), $this->getCacheMock());

        $mapper = $this->getFFProbeMapperMock();
        $mapper->expects($this->once())
            ->method('map')
            ->with($this->isType('string'), 'good data parsed')
            ->will($this->returnValue($output));

        $parser = $this->getFFProbeParserMock();
        $parser->expects($this->once())
            ->method('parse')
            ->with($this->isType('string', json_encode($data) . 'lala'))
            ->will($this->returnValue('good data parsed'));

        $tester = $this->getFFProbeOptionsTesterMockWithOptions($commands);

        $cache = $this->getCacheMock();
        $cache->expects($this->exactly(2))
            ->method('contains')
            ->will($this->returnValue(false));
        $cache->expects($this->never())
            ->method('fetch');

        $driver = $this->getFFProbeDriverMock();
        $driver->expects($this->exactly(2))
            ->method('command')
            ->will($this->returnValue(json_encode($data) . 'lala'));

        $ffprobe->setOptionsTester($tester)
            ->setCache($cache)
            ->setMapper($mapper)
            ->setFFProbeDriver($driver)
            ->setParser($parser);

        $this->assertEquals($output, call_user_func(array($ffprobe, $method), $pathfile));
    }

    public function provideProbingDataWithCache()
    {
        $stream = $this->getStreamMock();
        $format = $this->getFormatMock();

        return array(
            array($stream, 'streams'),
            array($format, 'format'),
        );
    }

    /**
     * @dataProvider provideProbingDataWithCache
     */
    public function testProbeWithCache($output, $method)
    {
        $pathfile = __FILE__;

        $ffprobe = new FFProbe($this->getFFProbeDriverMock(), $this->getCacheMock());

        $mapper = $this->getFFProbeMapperMock();
        $mapper->expects($this->never())
            ->method('map');

        $tester = $this->getFFProbeOptionsTesterMock();

        $cache = $this->getCacheMock();
        $cache->expects($this->once())
            ->method('contains')
            ->will($this->returnValue(true));
        $cache->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($output));
        $cache->expects($this->never())
            ->method('save');

        $driver = $this->getFFProbeDriverMock();
        $driver->expects($this->never())
            ->method('command');

        $ffprobe->setOptionsTester($tester)
            ->setCache($cache)
            ->setMapper($mapper)
            ->setFFProbeDriver($driver);

        $this->assertEquals($output, call_user_func(array($ffprobe, $method), $pathfile));
    }

    public function provideProbeMethod()
    {
        return array(
            array('streams'),
            array('format'),
        );
    }

    /**
     * @expectedException FFMpeg\Exception\RuntimeException
     * @dataProvider provideProbeMethod
     */
    public function testProbeWithoutShowStreamsAvailable($method)
    {
        $pathfile = __FILE__;

        $ffprobe = new FFProbe($this->getFFProbeDriverMock(), $this->getCacheMock());
        $ffprobe->setOptionsTester($this->getFFProbeOptionsTesterMock());
        call_user_func(array($ffprobe, $method), $pathfile);
    }

    /**
     * @dataProvider provideCreateOptions
     */
    public function testCreate($logger, $conf, $cache)
    {
        $finder = new ExecutableFinder();

        $found = false;
        foreach (array('avprobe', 'ffprobe') as $name) {
            if (null !== $finder->find($name)) {
                $found = true;
            }
        }

        if (!$found) {
            $this->markTestSkipped("Unable to find avprobe or ffprobe on system");
        }

        $ffprobe = FFProbe::create();
        $this->assertInstanceOf('FFMpeg\FFprobe', $ffprobe);

        $ffprobe = FFProbe::create($conf, $logger, $cache);
        $this->assertInstanceOf('FFMpeg\FFprobe', $ffprobe);

        if (null !== $cache) {
            $this->assertSame($cache, $ffprobe->getCache());
        }
        if (null !== $logger) {
            $this->assertSame($logger, $ffprobe->getFFProbeDriver()->getProcessRunner()->getLogger());
        }
        if ($conf instanceof ConfigurationInterface) {
            $this->assertSame($conf, $ffprobe->getFFProbeDriver()->getConfiguration());
        }
    }

    public function provideCreateOptions()
    {
        return array(
            array(null, array('key' => 'value'), null),
            array($this->getLoggerMock(), array('key' => 'value'), null),
            array(null, new Configuration(), null),
            array(null, array('key' => 'value'), $this->getCacheMock()),
        );
    }
}
