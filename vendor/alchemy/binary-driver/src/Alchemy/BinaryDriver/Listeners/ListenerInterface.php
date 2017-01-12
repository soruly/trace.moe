<?php

/*
 * This file is part of Alchemy\BinaryDriver.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\BinaryDriver\Listeners;

use Evenement\EventEmitterInterface;

interface ListenerInterface extends EventEmitterInterface
{
    /**
     * Handle the output of a ProcessRunner
     *
     * @param string $type The data type, one of Process::ERR, Process::OUT constants
     * @param string $data The output
     */
    public function handle($type, $data);

    /**
     * An array of events that should be forwarded to BinaryInterface
     *
     * @return array
     */
    public function forwardedEvents();
}
