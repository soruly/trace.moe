<?php

namespace Tests\FFMpeg\Unit\Filters\Audio;

use FFMpeg\Filters\Audio\AudioFilters;
use Tests\FFMpeg\Unit\TestCase;

class AudioFiltersTest extends TestCase
{
    public function testResample()
    {
        $capturedFilter = null;

        $audio = $this->getAudioMock();
        $audio->expects($this->once())
            ->method('addFilter')
            ->with($this->isInstanceOf('FFMpeg\Filters\Audio\AudioResamplableFilter'))
            ->will($this->returnCallback(function ($filter) use (&$capturedFilter) {
                $capturedFilter = $filter;
            }));

        $filters = new AudioFilters($audio);
        $filters->resample(8000);
        $this->assertEquals(8000, $capturedFilter->getRate());
    }
}
