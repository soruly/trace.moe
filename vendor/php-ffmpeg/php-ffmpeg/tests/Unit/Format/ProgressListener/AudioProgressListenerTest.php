<?php

namespace Tests\FFMpeg\Unit\Format\ProgressListener;

use Tests\FFMpeg\Unit\TestCase;
use FFMpeg\Format\ProgressListener\AudioProgressListener;
use FFMpeg\FFProbe\DataMapping\Format;

class AudioProgressListenerTest extends TestCase
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

        $listener = new AudioProgressListener($ffprobe, __FILE__, $currentPass, $totalPass);
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
                $phpunit->assertLessThan($expectedRate2 + 3, $rate);
                $phpunit->assertGreaterThan($expectedRate2 - 3, $rate);
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
                2894412,
                180.900750,
                'size=     712kB time=00:00:45.50 bitrate= 128.1kbits/s',
                25,
                0,
                0,
                'size=     1274kB time=00:01:29.32 bitrate= 142.8kbits/s',
                49,
                2,
                563,
                1,
                1
            ),
            array(
                2894412,
                180.900750,
                'size=     712kB time=00:00:45.50 bitrate= 128.1kbits/s',
                12,
                0,
                0,
                'size=     1274kB time=00:01:29.32 bitrate= 142.8kbits/s',
                24,
                2,
                563,
                1,
                2
            )
        );
    }
}
