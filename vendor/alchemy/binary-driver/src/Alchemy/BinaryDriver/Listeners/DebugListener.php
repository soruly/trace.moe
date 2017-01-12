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

use Evenement\EventEmitter;
use Symfony\Component\Process\Process;

class DebugListener extends EventEmitter implements ListenerInterface
{
    private $prefixOut;
    private $prefixErr;
    private $eventOut;
    private $eventErr;

    public function __construct($prefixOut = '[OUT] ', $prefixErr = '[ERROR] ', $eventOut = 'debug', $eventErr = 'debug')
    {
        $this->prefixOut = $prefixOut;
        $this->prefixErr = $prefixErr;
        $this->eventOut = $eventOut;
        $this->eventErr = $eventErr;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($type, $data)
    {
        if (Process::ERR === $type) {
            $this->emitLines($this->eventErr, $this->prefixErr, $data);
        } elseif (Process::OUT === $type) {
            $this->emitLines($this->eventOut, $this->prefixOut, $data);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function forwardedEvents()
    {
        return array_unique(array($this->eventErr, $this->eventOut));
    }

    private function emitLines($event, $prefix, $lines)
    {
        foreach (explode("\n", $lines) as $line) {
            $this->emit($event, array($prefix . $line));
        }
    }
}
