<?php

namespace Tests\FFMpeg\Functional;

use FFMpeg\FFMpeg;

abstract class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return FFMpeg
     */
    public function getFFMpeg()
    {
        return FFMpeg::create(array('timeout' => 300));
    }
}
