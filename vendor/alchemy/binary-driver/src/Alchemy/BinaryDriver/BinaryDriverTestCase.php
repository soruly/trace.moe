<?php

namespace Alchemy\BinaryDriver;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

/**
 * Convenient PHPUnit methods for testing BinaryDriverInterface implementations.
 */
class BinaryDriverTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return ProcessBuilderFactoryInterface
     */
    public function createProcessBuilderFactoryMock()
    {
        return $this->getMock('Alchemy\BinaryDriver\ProcessBuilderFactoryInterface');
    }

    /**
     * @param integer $runs        The number of runs expected
     * @param Boolean $success     True if the process expects to be successfull
     * @param string  $commandLine The commandline executed
     * @param string  $output      The process output
     * @param string  $error       The process error output
     *
     * @return Process
     */
    public function createProcessMock($runs = 1, $success = true, $commandLine = null, $output = null, $error = null, $callback = false)
    {
        $process = $this->getMockBuilder('Symfony\Component\Process\Process')
            ->disableOriginalConstructor()
            ->getMock();

        $builder = $process->expects($this->exactly($runs))
            ->method('run');

        if (true === $callback) {
            $builder->with($this->isInstanceOf('Closure'));
        }

        $process->expects($this->any())
            ->method('isSuccessful')
            ->will($this->returnValue($success));

        foreach (array(
            'getOutput' => $output,
            'getErrorOutput' => $error,
            'getCommandLine' => $commandLine,
        ) as $command => $value) {
            $process
                ->expects($this->any())
                ->method($command)
                ->will($this->returnValue($value));
        }

        return $process;
    }

    /**
     * @return LoggerInterface
     */
    public function createLoggerMock()
    {
        return $this->getMock('Psr\Log\LoggerInterface');
    }

    /**
     * @return ConfigurationInterface
     */
    public function createConfigurationMock()
    {
        return $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');
    }
}
