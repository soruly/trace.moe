<?php

/*
 * This file is part of Alchemy\BinaryDriver.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Tests\BinaryDriver;

use Alchemy\BinaryDriver\ProcessRunner;
use Alchemy\BinaryDriver\BinaryDriverTestCase;
use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use Alchemy\BinaryDriver\Listeners\ListenerInterface;
use Evenement\EventEmitter;
use Symfony\Component\Process\Exception\RuntimeException as ProcessRuntimeException;

class ProcessRunnerTest extends BinaryDriverTestCase
{
    public function getProcessRunner($logger)
    {
        return new ProcessRunner($logger, 'test-runner');
    }

    public function testRunSuccessFullProcess()
    {
        $logger = $this->createLoggerMock();
        $runner = $this->getProcessRunner($logger);

        $process = $this->createProcessMock(1, true, '--helloworld--', "Kikoo Romain", null, true);

        $logger
            ->expects($this->never())
            ->method('error');
        $logger
            ->expects($this->exactly(2))
            ->method('info');

        $this->assertEquals('Kikoo Romain', $runner->run($process, new \SplObjectStorage(), false));
    }

    public function testRunSuccessFullProcessBypassingErrors()
    {
        $logger = $this->createLoggerMock();
        $runner = $this->getProcessRunner($logger);

        $process = $this->createProcessMock(1, true, '--helloworld--', "Kikoo Romain", null, true);

        $logger
            ->expects($this->never())
            ->method('error');
        $logger
            ->expects($this->exactly(2))
            ->method('info');

        $this->assertEquals('Kikoo Romain', $runner->run($process, new \SplObjectStorage(), true));
    }

    public function testRunFailingProcess()
    {
        $logger = $this->createLoggerMock();
        $runner = $this->getProcessRunner($logger);

        $process = $this->createProcessMock(1, false, '--helloworld--', null, null, true);

        $logger
            ->expects($this->once())
            ->method('error');
        $logger
            ->expects($this->once())
            ->method('info');

        try {
            $runner->run($process, new \SplObjectStorage(), false);
            $this->fail('An exception should have been raised');
        } catch (ExecutionFailureException $e) {

        }
    }

    public function testRunFailingProcessWithException()
    {
        $logger = $this->createLoggerMock();
        $runner = $this->getProcessRunner($logger);

        $exception = new ProcessRuntimeException('Process Failed');
        $process = $this->getMockBuilder('Symfony\Component\Process\Process')
            ->disableOriginalConstructor()
            ->getMock();
        $process->expects($this->once())
            ->method('run')
            ->will($this->throwException($exception));

        $logger
            ->expects($this->once())
            ->method('error');
        $logger
            ->expects($this->once())
            ->method('info');

        try {
            $runner->run($process, new \SplObjectStorage(), false);
            $this->fail('An exception should have been raised');
        } catch (ExecutionFailureException $e) {
            $this->assertEquals($exception, $e->getPrevious());
        }
    }

    public function testRunfailingProcessBypassingErrors()
    {
        $logger = $this->createLoggerMock();
        $runner = $this->getProcessRunner($logger);

        $process = $this->createProcessMock(1, false, '--helloworld--', 'Hello output', null, true);

        $logger
            ->expects($this->once())
            ->method('error');
        $logger
            ->expects($this->once())
            ->method('info');

        $this->assertNull($runner->run($process, new \SplObjectStorage(), true));
    }

    public function testRunFailingProcessWithExceptionBypassingErrors()
    {
        $logger = $this->createLoggerMock();
        $runner = $this->getProcessRunner($logger);

        $exception = new ProcessRuntimeException('Process Failed');
        $process = $this->getMockBuilder('Symfony\Component\Process\Process')
            ->disableOriginalConstructor()
            ->getMock();
        $process->expects($this->once())
            ->method('run')
            ->will($this->throwException($exception));

        $logger
            ->expects($this->once())
            ->method('error');
        $logger
            ->expects($this->once())
            ->method('info');

        $this->assertNull($runner->run($process, new \SplObjectStorage(), true));
    }

    public function testRunSuccessFullProcessWithHandlers()
    {
        $logger = $this->createLoggerMock();
        $runner = $this->getProcessRunner($logger);

        $capturedCallback = null;

        $process = $this->createProcessMock(1, true, '--helloworld--', "Kikoo Romain", null, true);
        $process->expects($this->once())
            ->method('run')
            ->with($this->isInstanceOf('Closure'))
            ->will($this->returnCallback(function ($callback) use (&$capturedCallback) {
                $capturedCallback = $callback;
            }));

        $logger
            ->expects($this->never())
            ->method('error');
        $logger
            ->expects($this->exactly(2))
            ->method('info');

        $listener = new TestListener();
        $storage = new \SplObjectStorage();
        $storage->attach($listener);

        $capturedType = $capturedData = null;

        $listener->on('received', function ($type, $data) use (&$capturedType, &$capturedData) {
            $capturedData = $data;
            $capturedType = $type;
        });

        $this->assertEquals('Kikoo Romain', $runner->run($process, $storage, false));

        $type = 'err';
        $data = 'data';

        $capturedCallback($type, $data);

        $this->assertEquals($data, $capturedData);
        $this->assertEquals($type, $capturedType);
    }
}

class TestListener extends EventEmitter implements ListenerInterface
{
    public function handle($type, $data)
    {
        return $this->emit('received', array($type, $data));
    }

    public function forwardedEvents()
    {
        return array();
    }
}
