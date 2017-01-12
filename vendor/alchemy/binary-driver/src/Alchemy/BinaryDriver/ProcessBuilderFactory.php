<?php

/*
 * This file is part of Alchemy\BinaryDriver.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\BinaryDriver;

use Alchemy\BinaryDriver\Exception\InvalidArgumentException;
use Symfony\Component\Process\ProcessBuilder;

class ProcessBuilderFactory implements ProcessBuilderFactoryInterface
{
    /**
     * The binary path
     *
     * @var String
     */
    protected $binary;

    /**
     * The timeout for the generated processes
     *
     * @var integer|float
     */
    private $timeout;

    /**
     * An internal ProcessBuilder.
     *
     * Note that this one is used only if Symfony ProcessBuilder has method
     * setPrefix (2.3)
     *
     * @var ProcessBuilder
     */
    private $builder;

    /**
     * Tells whether Symfony LTS ProcessBuilder should be emulated or not.
     *
     * This symfony version provided a brand new ::setPrefix method.
     *
     * @var Boolean
     */
    public static $emulateSfLTS;

    /**
     * Constructor
     *
     * @param String $binary The path to the binary
     *
     * @throws InvalidArgumentException In case binary path is invalid
     */
    public function __construct($binary)
    {
        $this->detectEmulation();

        if (!self::$emulateSfLTS) {
            $this->builder = new ProcessBuilder();
        }

        $this->useBinary($binary);
    }

    /**
     * Covenient method for unit testing
     *
     * @return type
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Covenient method for unit testing
     *
     * @param  ProcessBuilder        $builder
     * @return ProcessBuilderFactory
     */
    public function setBuilder(ProcessBuilder $builder)
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBinary()
    {
        return $this->binary;
    }

    /**
     * @inheritdoc
     */
    public function useBinary($binary)
    {
        if (!is_executable($binary)) {
            throw new InvalidArgumentException(sprintf('`%s` is not an executable binary', $binary));
        }

        $this->binary = $binary;

        if (!static::$emulateSfLTS) {
            $this->builder->setPrefix($binary);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        if (!static::$emulateSfLTS) {
            $this->builder->setTimeout($this->timeout);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @inheritdoc
     */
    public function create($arguments = array())
    {
        if (null === $this->binary) {
            throw new InvalidArgumentException('No binary set');
        }

        if (!is_array($arguments)) {
            $arguments = array($arguments);
        }

        if (static::$emulateSfLTS) {
            array_unshift($arguments, $this->binary);

            return ProcessBuilder::create($arguments)
                ->setTimeout($this->timeout)
                ->getProcess();
        } else {
            return $this->builder
                ->setArguments($arguments)
                ->getProcess();
        }
    }

    private function detectEmulation()
    {
        if (null !== static::$emulateSfLTS) {
            return $this;
        }

        static::$emulateSfLTS = !method_exists('Symfony\Component\Process\ProcessBuilder', 'setPrefix');

        return $this;
    }
}
