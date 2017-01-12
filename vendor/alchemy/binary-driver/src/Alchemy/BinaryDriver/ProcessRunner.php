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

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use Psr\Log\LoggerInterface;
use SplObjectStorage;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class ProcessRunner implements ProcessRunnerInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $name;

    public function __construct(LoggerInterface $logger, $name)
    {
        $this->logger = $logger;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     *
     * @return ProcessRunner
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * {@inheritdoc}
     */
    public function run(Process $process, SplObjectStorage $listeners, $bypassErrors)
    {
        $this->logger->info(sprintf(
            '%s running command %s', $this->name, $process->getCommandLine()
        ));

        try {
            $process->run($this->buildCallback($listeners));
        } catch (RuntimeException $e) {
            if (!$bypassErrors) {
                $this->doExecutionFailure($process->getCommandLine(), $e);
            }
        }

        if (!$bypassErrors && !$process->isSuccessful()) {
            $this->doExecutionFailure($process->getCommandLine());
        } elseif (!$process->isSuccessful()) {
            $this->logger->error(sprintf(
                '%s failed to execute command %s', $this->name, $process->getCommandLine()
            ));

            return;
        } else {
            $this->logger->info(sprintf('%s executed command successfully', $this->name));

            return $process->getOutput();
        }
    }

    private function buildCallback(SplObjectStorage $listeners)
    {
        return function ($type, $data) use ($listeners) {
            foreach ($listeners as $listener) {
                $listener->handle($type, $data);
            }
        };
    }

    private function doExecutionFailure($command, \Exception $e = null)
    {
        $this->logger->error(sprintf(
            '%s failed to execute command %s', $this->name, $command
        ));
        throw new ExecutionFailureException(sprintf(
            '%s failed to execute command %s', $this->name, $command
        ), $e ? $e->getCode() : null, $e ?: null);
    }
}
