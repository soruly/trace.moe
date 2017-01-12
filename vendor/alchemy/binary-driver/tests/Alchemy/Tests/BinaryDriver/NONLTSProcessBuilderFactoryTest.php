<?php

namespace Alchemy\Tests\BinaryDriver;

use Alchemy\BinaryDriver\ProcessBuilderFactory;

class NONLTSProcessBuilderFactoryTest extends AbstractProcessBuilderFactoryTest
{
    protected function getProcessBuilderFactory($binary)
    {
        ProcessBuilderFactory::$emulateSfLTS = true;

        return new ProcessBuilderFactory($binary);
    }
}
