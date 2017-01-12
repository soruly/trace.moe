<?php

namespace Tests\FFMpeg\Unit\Coordinate;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Coordinate\TimeCode;

class TimeCodeTest extends TestCase
{
    /**
     * @dataProvider provideTimecodes
     */
    public function testFromString($timecode, $expected)
    {
        $tc = TimeCode::fromString($timecode);
        $this->assertEquals((string) $tc, $expected);
    }

    public function provideTimeCodes()
    {
        return array(
            array('1:02:04:05:20', '26:04:05.20'),
            array('1:02:04:05.20', '26:04:05.20'),
            array('02:04:05:20', '02:04:05.20'),
            array('02:04:05.20', '02:04:05.20'),
            array('00:00:05.20', '00:00:05.20'),
            array('00:00:00.00', '00:00:00.00'),
        );
    }

    /**
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testFromInvalidString()
    {
        TimeCode::fromString('lalali lala');
    }

    /**
     * @dataProvider provideSeconds
     */
    public function testFromSeconds($seconds, $expected)
    {
        $tc = TimeCode::fromSeconds($seconds);
        $this->assertEquals($expected, (string) $tc);
    }

    public function provideSeconds()
    {
        return array(
            array(0.467,  '00:00:00.47'),
            array(12.467, '00:00:12.47'),
            array(59.867, '00:00:59.87'),
            array(72.467, '00:01:12.47'),
            array(3599.467, '00:59:59.47'),
            array(3600.467, '01:00:00.47'),
            array(86422.467, '24:00:22.47'),
        );
    }
}
