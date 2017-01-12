<?php

namespace Tests\FFMpeg\Unit\FFProbe;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\FFProbe\OutputParser;
use FFMpeg\FFProbe;

class OutputParserTest extends TestCase
{
    /**
     * @dataProvider provideTypeDataAndOutput
     */
    public function testParse($type, $data, $expectedOutput)
    {
        $parser = new OutputParser();
        $this->assertEquals($expectedOutput, $parser->parse($type, $data));
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testParseWithInvalidArgument()
    {
        $parser = new OutputParser();
        $parser->parse('comme ca', 'data');
    }

    public function provideTypeDataAndOutput()
    {
        $expectedFormat = json_decode(file_get_contents(__DIR__ . '/../../fixtures/ffprobe/show_format.json'), true);
        $expectedStreams = json_decode(file_get_contents(__DIR__ . '/../../fixtures/ffprobe/show_streams.json'), true);

        $rawFormat = file_get_contents(__DIR__ . '/../../fixtures/ffprobe/show_format.raw');
        $rawStreams = file_get_contents(__DIR__ . '/../../fixtures/ffprobe/show_streams.raw');

        return array(
            array(FFProbe::TYPE_FORMAT, $rawFormat, $expectedFormat),
            array(FFProbe::TYPE_STREAMS, $rawStreams, $expectedStreams),
        );
    }
}
