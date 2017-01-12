<?php

namespace Tests\FFMpeg\Unit\Coordinate;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Coordinate\Dimension;

class DimensionTest extends TestCase
{
    /**
     * @dataProvider provideInvalidDimensions
     * @expectedException FFMpeg\Exception\InvalidArgumentException
     */
    public function testInvalidDimensions($width, $height)
    {
        new Dimension($width, $height);
    }

    public function provideInvalidDimensions()
    {
        return array(
            array(320, 0),
            array(320, -10),
            array(0, 240),
            array(-10, 240),
            array(0, 0),
            array(0, -10),
            array(-10, 0),
        );
    }

    public function testGetters()
    {
        $dimension = new Dimension(320, 240);
        $this->assertEquals(320, $dimension->getWidth());
        $this->assertEquals(240, $dimension->getHeight());
    }
}
