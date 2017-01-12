<?php

namespace Alchemy\Tests\BinaryDriver;

use Symfony\Component\Process\ExecutableFinder;
use Alchemy\BinaryDriver\ProcessBuilderFactory;

abstract class AbstractProcessBuilderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public static $phpBinary;

    private $original;
    /**
     * @return ProcessBuilderFactory
     */
    abstract protected function getProcessBuilderFactory($binary);

    public function setUp()
    {
        ProcessBuilderFactory::$emulateSfLTS = null;
        if (null === static::$phpBinary) {
            $this->markTestSkipped('Unable to detect php binary, skipping');
        }
    }

    public static function setUpBeforeClass()
    {
        $finder = new ExecutableFinder();
        static::$phpBinary = $finder->find('php');
    }

    public function testThatBinaryIsSetOnConstruction()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $this->assertEquals(static::$phpBinary, $factory->getBinary());
    }

    public function testGetSetBinary()
    {
        $finder = new ExecutableFinder();
        $phpUnit = $finder->find('phpunit');

        if (null === $phpUnit) {
            $this->markTestSkipped('Unable to detect phpunit binary, skipping');
        }

        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $factory->useBinary($phpUnit);
        $this->assertEquals($phpUnit, $factory->getBinary());
    }

    /**
     * @expectedException Alchemy\BinaryDriver\Exception\InvalidArgumentException
     */
    public function testUseNonExistantBinary()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $factory->useBinary('itissureitdoesnotexist');
    }

    public function testCreateShouldReturnAProcess()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $process = $factory->create();

        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
        $this->assertEquals("'".static::$phpBinary."'", $process->getCommandLine());
    }

    public function testCreateWithStringArgument()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $process = $factory->create('-v');

        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
        $this->assertEquals("'".static::$phpBinary."' '-v'", $process->getCommandLine());
    }

    public function testCreateWithArrayArgument()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $process = $factory->create(array('-r', 'echo "Hello !";'));

        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
        $this->assertEquals("'".static::$phpBinary."' '-r' 'echo \"Hello !\";'", $process->getCommandLine());
    }

    public function testCreateWithTimeout()
    {
        $factory = $this->getProcessBuilderFactory(static::$phpBinary);
        $factory->setTimeout(200);
        $process = $factory->create(array('-i'));

        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
        $this->assertEquals(200, $process->getTimeout());
    }
}
