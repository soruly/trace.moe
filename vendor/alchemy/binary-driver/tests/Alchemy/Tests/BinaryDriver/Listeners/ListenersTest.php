<?php

namespace Alchemy\Tests\BinaryDriver\Listeners;

use Alchemy\BinaryDriver\Listeners\Listeners;
use Evenement\EventEmitter;
use Alchemy\BinaryDriver\Listeners\ListenerInterface;

class ListenersTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $listener = new MockListener();

        $listeners = new Listeners();
        $listeners->register($listener);

        $n = 0;
        $listener->on('received', function ($type, $data) use (&$n, &$capturedType, &$capturedData) {
            $n++;
            $capturedData = $data;
            $capturedType = $type;
        });

        $type = 'type';
        $data = 'data';

        $listener->handle($type, $data);
        $listener->handle($type, $data);

        $listeners->unregister($listener);

        $listener->handle($type, $data);

        $this->assertEquals(3, $n);
        $this->assertEquals($type, $capturedType);
        $this->assertEquals($data, $capturedData);
    }

    public function testRegisterAndForwardThenUnregister()
    {
        $listener = new MockListener();
        $target = new EventEmitter();

        $n = 0;
        $target->on('received', function ($type, $data) use (&$n, &$capturedType, &$capturedData) {
            $n++;
            $capturedData = $data;
            $capturedType = $type;
        });

        $m = 0;
        $listener->on('received', function ($type, $data) use (&$m, &$capturedType2, &$capturedData2) {
            $m++;
            $capturedData2 = $data;
            $capturedType2 = $type;
        });

        $listeners = new Listeners();
        $listeners->register($listener, $target);

        $type = 'type';
        $data = 'data';

        $listener->handle($type, $data);
        $listener->handle($type, $data);

        $listeners->unregister($listener, $target);

        $listener->handle($type, $data);

        $this->assertEquals(2, $n);
        $this->assertEquals(3, $m);
        $this->assertEquals($type, $capturedType);
        $this->assertEquals($data, $capturedData);
        $this->assertEquals($type, $capturedType2);
        $this->assertEquals($data, $capturedData2);
    }
}

class MockListener extends EventEmitter implements ListenerInterface
{
    public function handle($type, $data)
    {
        $this->emit('received', array($type, $data));
    }

    public function forwardedEvents()
    {
        return array('received');
    }
}
