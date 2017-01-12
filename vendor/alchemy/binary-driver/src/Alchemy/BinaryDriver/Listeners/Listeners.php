<?php

namespace Alchemy\BinaryDriver\Listeners;

use SplObjectStorage;
use Evenement\EventEmitter;

class Listeners extends EventEmitter
{
    /** @var SplObjectStorage */
    public $storage;

    public function __construct()
    {
        $this->storage = new SplObjectStorage();
    }

    public function __clone()
    {
        $storage = $this->storage;
        $this->storage = new SplObjectStorage();
        $this->storage->addAll($storage);
    }

    /**
     * Registers a listener, pass the listener events to the target.
     *
     * @param ListenerInterface $listener
     * @param null|EventEmitter $target
     *
     * @return ListenersInterface
     */
    public function register(ListenerInterface $listener, EventEmitter $target = null)
    {
        $EElisteners = array();

        if (null !== $target) {
            $EElisteners = $this->forwardEvents($listener, $target, $listener->forwardedEvents());
        }

        $this->storage->attach($listener, $EElisteners);

        return $this;
    }

    /**
     * Unregisters a listener, removes the listener events from the target.
     *
     * @param ListenerInterface $listener
     *
     * @return ListenersInterface
     *
     * @throws InvalidArgumentException In case the listener is not registered
     */
    public function unregister(ListenerInterface $listener)
    {
        if (!isset($this->storage[$listener])) {
            throw new InvalidArgumentException('Listener is not registered.');
        }

        foreach ($this->storage[$listener] as $event => $EElistener) {
            $listener->removeListener($event, $EElistener);
        }

        $this->storage->detach($listener);

        return $this;
    }

    private function forwardEvents($source, $target, array $events)
    {
        $EElisteners = array();

        foreach ($events as $event) {
            $listener = $this->createListener($event, $target);
            $source->on($event, $EElisteners[$event] = $listener);
        }

        return $EElisteners;
    }

    private function createListener($event, $target)
    {
        return function () use ($event, $target) {
            $target->emit($event, func_get_args());
        };
    }
}
