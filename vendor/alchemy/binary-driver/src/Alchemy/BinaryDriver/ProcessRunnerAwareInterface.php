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

interface ProcessRunnerAwareInterface
{
    /**
     * Returns the current process runner
     *
     * @return ProcessRunnerInterface
     */
    public function getProcessRunner();

    /**
     * Sets a process runner
     *
     * @param ProcessRunnerInterface $runner
     */
    public function setProcessRunner(ProcessRunnerInterface $runner);
}
