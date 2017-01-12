<?php

namespace Tests\FFMpeg\Unit\Format\ProgressListener;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Format\ProgressListener\VideoProgressListener;
use FFMpeg\FFProbe\DataMapping\Format;

class VideoProgressListenerTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function testHandle($size, $duration,
        $data, $expectedPercent, $expectedRemaining, $expectedRate,
        $data2, $expectedPercent2, $expectedRemaining2, $expectedRate2,
        $currentPass, $totalPass
    )
    {
        $ffprobe = $this->getFFProbeMock();
        $ffprobe->expects($this->once())
            ->method('format')
            ->with(__FILE__)
            ->will($this->returnValue(new Format(array(
                'size'     => $size,
                'duration' => $duration,
            ))));

        $listener = new VideoProgressListener($ffprobe, __FILE__, $currentPass, $totalPass);
        $phpunit = $this;
        $n = 0;
        $listener->on('progress', function ($percent, $remaining, $rate) use (&$n, $phpunit, $expectedPercent, $expectedRemaining, $expectedRate, $expectedPercent2, $expectedRemaining2, $expectedRate2) {
            if (0 === $n) {
                $phpunit->assertEquals($expectedPercent, $percent);
                $phpunit->assertEquals($expectedRemaining, $remaining);
                $phpunit->assertEquals($expectedRate, $rate);
            } elseif (1 === $n) {
                $phpunit->assertEquals($expectedPercent2, $percent);
                $phpunit->assertEquals($expectedRemaining2, $remaining);
                $phpunit->assertLessThan($expectedRate2 + 10, $rate);
                $phpunit->assertGreaterThan($expectedRate2 - 10, $rate);
            }
            $n++;
        });
        // first one does not trigger progress event
        $listener->handle('any-type'.mt_rand(), $data);
        sleep(1);
        $listener->handle('any-type'.mt_rand(), $data);
        sleep(1);
        $listener->handle('any-type'.mt_rand(), $data2);
        $this->assertEquals(2, $n);
    }

    public function provideData()
    {
        return array(
            array(
                147073958,
                281.147533,
                'frame=  206 fps=202 q=10.0 size=     571kB time=00:00:07.12 bitrate= 656.8kbits/s dup=9 drop=0',
                2,
                0,
                0,
                'frame=  854 fps=113 q=20.0 size=    4430kB time=00:00:33.04 bitrate=1098.5kbits/s dup=36 drop=0',
                11,
                32,
                3868,
                1,
                1
            ),
            array(
                147073958,
                281.147533,
                'frame=  206 fps=202 q=10.0 size=     571kB time=00:00:07.12 bitrate= 656.8kbits/s dup=9 drop=0',
                1,
                0,
                0,
                'frame=  854 fps=113 q=20.0 size=    4430kB time=00:00:33.04 bitrate=1098.5kbits/s dup=36 drop=0',
                5,
                32,
                3868,
                1,
                2
            )
        );
    }
}
