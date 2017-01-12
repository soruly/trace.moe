<?php

namespace Tests\FFMpeg\Unit\FFProbe\DataMapping;

use FFMpeg\Coordinate\Dimension;
use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\FFProbe\DataMapping\Stream;

class StreamTest extends TestCase
{
    /**
     * @dataProvider provideAudioCases
     */
    public function testIsAudio($isAudio, $properties)
    {
        $stream = new Stream($properties);
        $this->assertTrue($isAudio === $stream->isAudio());
    }

    public function provideAudioCases()
    {
        return array(
            array(true, array('codec_type' => 'audio')),
            array(false, array('codec_type' => 'video')),
        );
    }

    /**
     * @dataProvider provideVideoCases
     */
    public function testIsVideo($isVideo, $properties)
    {
        $stream = new Stream($properties);
        $this->assertTrue($isVideo === $stream->isVideo());
    }

    public function provideVideoCases()
    {
        return array(
            array(true, array('codec_type' => 'video')),
            array(false, array('codec_type' => 'audio')),
        );
    }

    /**
     * @expectedException FFMpeg\Exception\LogicException
     * @expectedExceptionMessage Dimensions can only be retrieved from video streams.
     */
    public function testGetDimensionsFromAudio()
    {
        $stream = new Stream(array('codec_type' => 'audio'));
        $stream->getDimensions();
    }

    public function testGetDimensionsFromVideo()
    {
        $stream = new Stream(array('codec_type' => 'video', 'width' => 960, 'height' => 720));
        $this->assertEquals(new Dimension(960, 720), $stream->getDimensions());
    }

    /**
     * @dataProvider provideInvalidPropertiesForDimensionsExtraction
     * @expectedException FFMpeg\Exception\RuntimeException
     * @expectedExceptionMessage Unable to extract dimensions.
     */
    public function testUnableToGetDimensionsFromVideo($properties)
    {
        $stream = new Stream(array('codec_type' => 'video', 'width' => 960));
        $stream->getDimensions();
    }

    public function provideInvalidPropertiesForDimensionsExtraction()
    {
        return array(
            array('codec_type' => 'video', 'width' => 960),
            array('codec_type' => 'video', 'height' => 960),
        );
    }

    /**
     * @dataProvider providePropertiesForDimensionsExtraction
     */
    public function testGetDimensionsFromVideoWithDisplayRatio($data)
    {
        $stream = new Stream(array(
            'codec_type' => 'video',
            'width' => $data['width'],
            'height' =>  $data['height'],
            'sample_aspect_ratio' =>  $data['sar'],
            'display_aspect_ratio' =>  $data['dar']
        ));
        $this->assertEquals(new Dimension($data['result_width'], $data['result_height']), $stream->getDimensions());
    }

    /**
     * @dataProvider provideInvalidRatios
     */
    public function testGetDimensionsFromVideoWithInvalidDisplayRatio($invalidRatio)
    {
        $stream = new Stream(array('codec_type' => 'video', 'width' => 960, 'height' => 720, 'sample_aspect_ratio' => $invalidRatio, 'display_aspect_ratio' => '16:9'));
        $this->assertEquals(new Dimension(960, 720), $stream->getDimensions());
    }

    public function provideInvalidRatios()
    {
        return array(array('0:1'), array('2:1:3'));
    }

    public function providePropertiesForDimensionsExtraction()
    {
        return array(
            array(
                array('width' => '960', 'height' => '720',
                'sar' => '4:3', 'dar' => '16:9',
                'result_width' => '1280', 'result_height' => '720'),
            ),
            array(
                array('width' => '1920', 'height' => '1080',
                'sar' => '1:1', 'dar' => '16:9',
                'result_width' => '1920', 'result_height' => '1080'),
            ),
            array(
                array('width' => '640', 'height' => '480',
                'sar' => '75:74', 'dar' => '50:37',
                'result_width' => '649', 'result_height' => '480'),
            ),
            array(
                array('width' => '720', 'height' => '576',
                  'sar' => '52:28', 'dar' => '16:9',
                  'result_width' => '1337', 'result_height' => '752'),
            ),
        );
    }
}
