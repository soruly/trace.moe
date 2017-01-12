CHANGELOG
---------
* 1.6.0 (2015-03-02)
  * BC Break: bump minimum PHP versions 
  * Allow use of evenement v2.0 (thanks @patkar for the P/R)

* 1.5.0 (2013-06-21)

  * BC Break : ConfigurationInterface::get does not throw exceptions anymore
    in case the key does not exist. Second argument is a default value to return
    in case the key does not exist.

* 1.4.1 (2013-05-23)

  * Add third parameter to BinaryInterface::command method to pass a listener or
    an array of listener that will be registered just the time of the command.

* 1.4.0 (2013-05-11)

  * Extract process run management to ProcessRunner.
  * Add support for process listeners.
  * Provides bundled DebugListener.
  * Add BinaryInterface::command method.
  * BC break : ProcessRunnerInterface::run now takes an SplObjectStorage containing
    listeners as second argument.
  * BC break : BinaryInterface no longer implements LoggerAwareInterface
    as it is now supported by ProcessRunner.

* 1.3.4 (2013-04-26)

  * Add BinaryDriver::run method.

* 1.3.3 (2013-04-26)

  * Add BinaryDriver::createProcessMock method.

* 1.3.2 (2013-04-26)

  * Add BinaryDriverTestCase for testing BinaryDriver implementations.

* 1.3.1 (2013-04-24)

  * Add timeouts handling

* 1.3.0 (2013-04-24)

  * Add BinaryInterface and AbstractBinary

* 1.2.1 (2013-04-24)

  * Add ConfigurationAwareInterface
  * Add ProcessBuilderAwareInterface

* 1.2.0 (2013-04-24)

  * Add BinaryDriver\Configuration

* 1.1.0 (2013-04-24)

  * Add support for timeouts via `setTimeout` method

* 1.0.0 (2013-04-23)

  * First stable version.
