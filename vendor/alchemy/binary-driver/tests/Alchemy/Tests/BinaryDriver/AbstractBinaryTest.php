<?php

namespace Alchemy\Tests\BinaryDriver;

use Alchemy\BinaryDriver\AbstractBinary;
use Alchemy\BinaryDriver\BinaryDriverTestCase;
use Alchemy\BinaryDriver\Configuration;
use Alchemy\BinaryDriver\Exception\ExecutableNotFoundException;
use Alchemy\BinaryDriver\Listeners\ListenerInterface;
use Symfony\Component\Process\ExecutableFinder;

class AbstractBinaryTest extends BinaryDriverTestCase
{
    protected function getPhpBinary()
    {
        $finder = new ExecutableFinder();
        $php = $finder->find('php');

        if (null === $php) {
            $this->markTestSkipped('Unable to find a php binary');
        }

        return $php;
    }

    public function testSimpleLoadWithBinaryPath()
    {
        $php = $this->getPhpBinary();
        $imp = Implementation::load($php);
        $this->assertInstanceOf('Alchemy\Tests\BinaryDriver\Implementation', $imp);

        $this->assertEquals($php, $imp->getProcessBuilderFactory()->getBinary());
    }

    public function testMultipleLoadWithBinaryPath()
    {
        $php = $this->getPhpBinary();
        $imp = Implementation::load(array('/zz/path/to/unexisting/command', $php));
        $this->assertInstanceOf('Alchemy\Tests\BinaryDriver\Implementation', $imp);

        $this->assertEquals($php, $imp->getProcessBuilderFactory()->getBinary());
    }

    public function testSimpleLoadWithBinaryName()
    {
        $php = $this->getPhpBinary();
        $imp = Implementation::load('php');
        $this->assertInstanceOf('Alchemy\Tests\BinaryDriver\Implementation', $imp);

        $this->assertEquals($php, $imp->getProcessBuilderFactory()->getBinary());
    }

    public function testMultipleLoadWithBinaryName()
    {
        $php = $this->getPhpBinary();
        $imp = Implementation::load(array('bachibouzouk', 'php'));
        $this->assertInstanceOf('Alchemy\Tests\BinaryDriver\Implementation', $imp);

        $this->assertEquals($php, $imp->getProcessBuilderFactory()->getBinary());
    }

    public function testLoadWithMultiplePathExpectingAFailure()
    {
        $this->setExpectedException(ExecutableNotFoundException::class);

        Implementation::load(array('bachibouzouk', 'moribon'));
    }

    public function testLoadWithUniquePathExpectingAFailure()
    {
        $this->setExpectedException(ExecutableNotFoundException::class);

        Implementation::load('bachibouzouk');
    }

    public function testLoadWithCustomLogger()
    {
        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $imp = Implementation::load('php', $logger);

        $this->assertEquals($logger, $imp->getProcessRunner()->getLogger());
    }

    public function testLoadWithCustomConfigurationAsArray()
    {
        $conf = array('timeout' => 200);
        $imp = Implementation::load('php', null, $conf);

        $this->assertEquals($conf, $imp->getConfiguration()->all());
    }

    public function testLoadWithCustomConfigurationAsObject()
    {
        $conf = $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');
        $imp = Implementation::load('php', null, $conf);

        $this->assertEquals($conf, $imp->getConfiguration());
    }

    public function testProcessBuilderFactoryGetterAndSetters()
    {
        $imp = Implementation::load('php');
        $factory = $this->getMock('Alchemy\BinaryDriver\ProcessBuilderFactoryInterface');

        $imp->setProcessBuilderFactory($factory);
        $this->assertEquals($factory, $imp->getProcessBuilderFactory());
    }

    public function testConfigurationGetterAndSetters()
    {
        $imp = Implementation::load('php');
        $conf = $this->getMock('Alchemy\BinaryDriver\ConfigurationInterface');

        $imp->setConfiguration($conf);
        $this->assertEquals($conf, $imp->getConfiguration());
    }

    public function testTimeoutIsSetOnConstruction()
    {
        $imp = Implementation::load('php', null, array('timeout' => 42));
        $this->assertEquals(42, $imp->getProcessBuilderFactory()->getTimeout());
    }

    public function testTimeoutIsSetOnConfigurationSetting()
    {
        $imp = Implementation::load('php', null);
        $imp->setConfiguration(new Configuration(array('timeout' => 42)));
        $this->assertEquals(42, $imp->getProcessBuilderFactory()->getTimeout());
    }

    public function testTimeoutIsSetOnProcessBuilderSetting()
    {
        $imp = Implementation::load('php', null, array('timeout' => 42));

        $factory = $this->getMock('Alchemy\BinaryDriver\ProcessBuilderFactoryInterface');
        $factory->expects($this->once())
            ->method('setTimeout')
            ->with(42);

        $imp->setProcessBuilderFactory($factory);
    }

    public function testListenRegistersAListener()
    {
        $imp = Implementation::load('php');

        $listeners = $this->getMockBuilder('Alchemy\BinaryDriver\Listeners\Listeners')
            ->disableOriginalConstructor()
            ->getMock();

        $listener = $this->getMock('Alchemy\BinaryDriver\Listeners\ListenerInterface');

        $listeners->expects($this->once())
            ->method('register')
            ->with($this->equalTo($listener), $this->equalTo($imp));

        $reflexion = new \ReflectionClass('Alchemy\BinaryDriver\AbstractBinary');
        $prop = $reflexion->getProperty('listenersManager');
        $prop->setAccessible(true);
        $prop->setValue($imp, $listeners);

        $imp->listen($listener);
    }

    /**
     * @dataProvider provideCommandParameters
     */
    public function testCommandRunsAProcess($parameters, $bypassErrors, $expectedParameters, $output)
    {
        $imp = Implementation::load('php');
        $factory = $this->getMock('Alchemy\BinaryDriver\ProcessBuilderFactoryInterface');
        $processRunner = $this->getMock('Alchemy\BinaryDriver\ProcessRunnerInterface');

        $process = $this->getMockBuilder('Symfony\Component\Process\Process')
            ->disableOriginalConstructor()
            ->getMock();

        $processRunner->expects($this->once())
            ->method('run')
            ->with($this->equalTo($process), $this->isInstanceOf('SplObjectStorage'), $this->equalTo($bypassErrors))
            ->will($this->returnValue($output));

        $factory->expects($this->once())
            ->method('create')
            ->with($expectedParameters)
            ->will($this->returnValue($process));

        $imp->setProcessBuilderFactory($factory);
        $imp->setProcessRunner($processRunner);

        $this->assertEquals($output, $imp->command($parameters, $bypassErrors));
    }

    /**
     * @dataProvider provideCommandWithListenersParameters
     */
    public function testCommandWithTemporaryListeners($parameters, $bypassErrors, $expectedParameters, $output, $count, $listeners)
    {
        $imp = Implementation::load('php');
        $factory = $this->getMock('Alchemy\BinaryDriver\ProcessBuilderFactoryInterface');
        $processRunner = $this->getMock('Alchemy\BinaryDriver\ProcessRunnerInterface');

        $process = $this->getMockBuilder('Symfony\Component\Process\Process')
            ->disableOriginalConstructor()
            ->getMock();

        $firstStorage = $secondStorage = null;

        $processRunner->expects($this->exactly(2))
            ->method('run')
            ->with($this->equalTo($process), $this->isInstanceOf('SplObjectStorage'), $this->equalTo($bypassErrors))
            ->will($this->returnCallback(function ($process, $storage, $errors) use ($output, &$firstStorage, &$secondStorage) {
                if (null === $firstStorage) {
                    $firstStorage = $storage;
                } else {
                    $secondStorage = $storage;
                }

                return $output;
            }));

        $factory->expects($this->exactly(2))
            ->method('create')
            ->with($expectedParameters)
            ->will($this->returnValue($process));

        $imp->setProcessBuilderFactory($factory);
        $imp->setProcessRunner($processRunner);

        $this->assertEquals($output, $imp->command($parameters, $bypassErrors, $listeners));
        $this->assertCount($count, $firstStorage);
        $this->assertEquals($output, $imp->command($parameters, $bypassErrors));
        $this->assertCount(0, $secondStorage);
    }

    public function provideCommandWithListenersParameters()
    {
        return array(
            array('-a', false, array('-a'), 'loubda', 2, array($this->getMockListener(), $this->getMockListener())),
            array('-a', false, array('-a'), 'loubda', 1, array($this->getMockListener())),
            array('-a', false, array('-a'), 'loubda', 1, $this->getMockListener()),
            array('-a', false, array('-a'), 'loubda', 0, array()),
        );
    }

    public function provideCommandParameters()
    {
        return array(
            array('-a', false, array('-a'), 'loubda'),
            array('-a', true, array('-a'), 'loubda'),
            array('-a -b', false, array('-a -b'), 'loubda'),
            array(array('-a'), false, array('-a'), 'loubda'),
            array(array('-a'), true, array('-a'), 'loubda'),
            array(array('-a', '-b'), false, array('-a', '-b'), 'loubda'),
        );
    }

    public function testUnlistenUnregistersAListener()
    {
        $imp = Implementation::load('php');

        $listeners = $this->getMockBuilder('Alchemy\BinaryDriver\Listeners\Listeners')
            ->disableOriginalConstructor()
            ->getMock();

        $listener = $this->getMock('Alchemy\BinaryDriver\Listeners\ListenerInterface');

        $listeners->expects($this->once())
            ->method('unregister')
            ->with($this->equalTo($listener), $this->equalTo($imp));

        $reflexion = new \ReflectionClass('Alchemy\BinaryDriver\AbstractBinary');
        $prop = $reflexion->getProperty('listenersManager');
        $prop->setAccessible(true);
        $prop->setValue($imp, $listeners);

        $imp->unlisten($listener);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockListener()
    {
        $listener = $this->getMock(ListenerInterface::class);
        $listener->expects($this->any())
            ->method('forwardedEvents')
            ->willReturn(array());

        return $listener;
    }
}

class Implementation extends AbstractBinary
{
    public function getName()
    {
        return 'Implementation';
    }
}
