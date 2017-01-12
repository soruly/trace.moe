# Binary Driver

Binary-Driver is a set of PHP tools to build binary drivers.

[![Build Status](https://travis-ci.org/alchemy-fr/BinaryDriver.png?branch=master)](https://travis-ci.org/alchemy-fr/BinaryDriver)

## Why ?

You may wonder *Why building a library while I can use `exec` or
[symfony/process](https://github.com/symfony/Process) ?*.

Here is a simple answer :

 - If you use `exec`, `passthru`, `system`, `proc_open` or any low level process
   handling in PHP, you should have a look to [symfony/process](https://github.com/symfony/Process)
   component that will provide an OO portable, testable and secure interface to
   deal with this. It seems easy at first approach, but if you look at this
   component [unit tests](https://github.com/symfony/Process/tree/master/Tests),
   you will see that handling process in a simple interface can easily become a
   nightmare.

 - If you already use symfony/process, and want to build binary drivers, you
   will always have the same common set of methods and objects to configure, log,
   debug, and generate processes.
   This library is a base to implement any binary driver with this common set of
   needs.

## AbstractBinary

`AbstractBinary` provides an abstract class to build a binary driver. It implements
`BinaryInterface`.

Implementation example :

```php
use Alchemy\BinaryDriver\AbstractBinary;

class LsDriver extends AbstractBinary
{
    public function getName()
    {
        return 'ls driver';
    }
}

$parser = new LsParser();

$driver = Driver::load('ls');
// will return the output of `ls -a -l`
$parser->parse($driver->command(array('-a', '-l')));
```

### Binary detection troubleshooting

If you are using Nginx with PHP-fpm, executable detection may not work because of an empty `$_ENV['path']`. 
To avoid having an empty `PATH` environment variable, add the following line to your `fastcgi_params` 
config file (replace `/your/current/path/` with the output of `printenv PATH`) :

```
fastcgi_param    PATH    /your/current/path
```

## Logging

You can log events with a `Psr\Log\LoggerInterface` by passing it in the load
method as second argument :

```php
$logger = new Monolog\Logger('driver');
$driver = Driver::load('ls', $logger);
```

## Listeners

You can add custom listeners on processes.
Listeners are built on top of [Evenement](https://github.com/igorw/evenement)
and must implement `Alchemy\BinaryDriver\ListenerInterface`.

```php
use Symfony\Component\Process\Process;

class DebugListener extends EventEmitter implements ListenerInterface
{
    public function handle($type, $data)
    {
        foreach (explode(PHP_EOL, $data) as $line) {
            $this->emit($type === Process::ERR ? 'error' : 'out', array($line));
        }
    }

    public function forwardedEvents()
    {
        // forward 'error' events to the BinaryInterface
        return array('error');
    }
}

$listener = new DebugListener();

$driver = CustomImplementation::load('php');

// adds listener
$driver->listen($listener);

$driver->on('error', function ($line) {
    echo '[ERROR] ' . $line . PHP_EOL;
});

// removes listener
$driver->unlisten($listener);
```

### Bundled listeners

The debug listener is a simple listener to catch `stderr` and `stdout` outputs ;
read the implementation for customization.

```php
use Alchemy\BinaryDriver\Listeners\DebugListener;

$driver = CustomImplementation::load('php');
$driver->listen(new DebugListener());

$driver->on('debug', function ($line) {
    echo $line;
});
```

## ProcessBuilderFactory

ProcessBuilderFactory ease spawning processes by generating Symfony [Process]
(http://symfony.com/doc/master/components/process.html) objects.

```php
use Alchemy\BinaryDriver\ProcessBuilderFactory;

$factory = new ProcessBuilderFactory('/usr/bin/php');

// return a Symfony\Component\Process\Process
$process = $factory->create('-v');

// echoes '/usr/bin/php' '-v'
echo $process->getCommandLine();

$process = $factory->create(array('-r', 'echo "Hello !";'));

// echoes '/usr/bin/php' '-r' 'echo "Hello !";'
echo $process->getCommandLine();
```

## Configuration

A simple configuration object, providing an `ArrayAccess` and `IteratorAggregate`
interface.

```php
use Alchemy\BinaryDriver\Configuration;

$conf = new Configuration(array('timeout' => 0));

echo $conf->get('timeout');

if ($conf->has('param')) {
    $conf->remove('param');
}

$conf->set('timeout', 20);

$conf->all();
```

Same example using the `ArrayAccess` interface :

```php
use Alchemy\BinaryDriver\Configuration;

$conf = new Configuration(array('timeout' => 0));

echo $conf['timeout'];

if (isset($conf['param'])) {
    unset($conf['param']);
}

$conf['timeout'] = 20;
```

## License

This project is released under the MIT license.
