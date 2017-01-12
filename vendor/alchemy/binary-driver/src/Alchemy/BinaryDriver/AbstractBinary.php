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

use Alchemy\BinaryDriver\Exception\ExecutableNotFoundException;
use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use Alchemy\BinaryDriver\Listeners\Listeners;
use Alchemy\BinaryDriver\Listeners\ListenerInterface;
use Evenement\EventEmitter;
use Monolog\Logger;
use Monolog\Handler\NullHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

abstract class AbstractBinary extends EventEmitter implements BinaryInterface
{
    /** @var ConfigurationInterface */
    protected $configuration;

    /** @var ProcessBuilderFactoryInterface */
    protected $factory;

    /** @var ProcessRunner */
    private $processRunner;

    /** @var Listeners */
    private $listenersManager;

    public function __construct(ProcessBuilderFactoryInterface $factory, LoggerInterface $logger, ConfigurationInterface $configuration)
    {
        $this->factory = $factory;
        $this->configuration = $configuration;
        $this->processRunner = new ProcessRunner($logger, $this->getName());
        $this->listenersManager = new Listeners();
        $this->applyProcessConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function listen(ListenerInterface $listener)
    {
        $this->listenersManager->register($listener, $this);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function unlisten(ListenerInterface $listener)
    {
        $this->listenersManager->unregister($listener, $this);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritdoc}
     *
     * @return BinaryInterface
     */
    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
        $this->applyProcessConfiguration();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessBuilderFactory()
    {
        return $this->factory;
    }

    /**
     * {@inheritdoc}
     *
     * @return BinaryInterface
     */
    public function setProcessBuilderFactory(ProcessBuilderFactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->applyProcessConfiguration();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessRunner()
    {
        return $this->processRunner;
    }

    /**
     * {@inheritdoc}
     */
    public function setProcessRunner(ProcessRunnerInterface $runner)
    {
        $this->processRunner = $runner;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function command($command, $bypassErrors = false, $listeners = null)
    {
        if (!is_array($command)) {
            $command = array($command);
        }

        return $this->run($this->factory->create($command), $bypassErrors, $listeners);
    }

    /**
     * {@inheritdoc}
     */
    public static function load($binaries, LoggerInterface $logger = null, $configuration = array())
    {
        $finder = new ExecutableFinder();
        $binary = null;
        $binaries = is_array($binaries) ? $binaries : array($binaries);

        foreach ($binaries as $candidate) {
            if (file_exists($candidate) && is_executable($candidate)) {
                $binary = $candidate;
                break;
            }
            if (null !== $binary = $finder->find($candidate)) {
                break;
            }
        }

        if (null === $binary) {
            throw new ExecutableNotFoundException(sprintf(
                'Executable not found, proposed : %s', implode(', ', $binaries)
            ));
        }

        if (null === $logger) {
            $logger = new Logger(__NAMESPACE__ . ' logger');
            $logger->pushHandler(new NullHandler());
        }

        $configuration = $configuration instanceof ConfigurationInterface ? $configuration : new Configuration($configuration);

        return new static(new ProcessBuilderFactory($binary), $logger, $configuration);
    }

    /**
     * Returns the name of the driver
     *
     * @return string
     */
    abstract public function getName();

    /**
     * Executes a process, logs events
     *
     * @param Process                 $process
     * @param Boolean                 $bypassErrors Set to true to disable throwing ExecutionFailureExceptions
     * @param ListenerInterface|array $listeners    A listener or an array of listener to register for this unique run
     *
     * @return string The Process output
     *
     * @throws ExecutionFailureException in case of process failure.
     */
    protected function run(Process $process, $bypassErrors = false, $listeners = null)
    {
        if (null !== $listeners) {
            if (!is_array($listeners)) {
                $listeners = array($listeners);
            }

            $listenersManager = clone $this->listenersManager;

            foreach ($listeners as $listener) {
                $listenersManager->register($listener, $this);
            }
        } else {
            $listenersManager = $this->listenersManager;
        }

        return $this->processRunner->run($process, $listenersManager->storage, $bypassErrors);
    }

    private function applyProcessConfiguration()
    {
        if ($this->configuration->has('timeout')) {
            $this->factory->setTimeout($this->configuration->get('timeout'));
        }

        return $this;
    }
}
