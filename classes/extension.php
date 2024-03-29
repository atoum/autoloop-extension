<?php

namespace atoum\atoum\autoloop;

use atoum\atoum;
use atoum\atoum\observable;
use atoum\atoum\runner;
use atoum\atoum\test;

class extension implements atoum\extension
{

    /**
     * @var configuration
     */
    protected $configuration;

    /**
     * @param atoum\configurator $configurator
     */
    public function __construct(atoum\configurator $configurator = null)
    {
        $this->configuration = $configuration = new configuration();

        if ($configurator)
        {
            $script = $configurator->getScript();
            $testHandler = function($script, $argument, $values) {
                $script->getRunner()->addTestsFromDirectory(dirname(__DIR__) . '/tests/units/classes');
            };

            $script
                ->addArgumentHandler($testHandler, array('--test-ext'))
                ->addArgumentHandler($testHandler, array('--test-it'))
            ;

            $autoLoopHandler = function(atoum\scripts\runner $script, $argument, $values) use ($configuration) {
                $script->enableLoopMode();

                $watcherStrategy = new scripts\runner\loopers\watcher($script);
                $watcherStrategy->setConfiguration($configuration);

                $script->setLooper($watcherStrategy);
            };

            $script
                ->addArgumentHandler(
                    $autoLoopHandler,
                    array('--autoloop'),
                    null,
                    $script->getLocale()->_('Automatically relaunch tests on file modification (implies --loop)')
                )
            ;
        }
    }


    /**
     * @param array $watchedFiles
     *
     * @return $this
     */
    public function setWatchedFiles(array $watchedFiles = array())
    {
        $this->configuration->setWatchedFiles($watchedFiles);

        return $this;
    }

    /**
     * @param runner $runner
     *
     * @return $this
     */
    public function setRunner(runner $runner)
    {
        return $this;
    }

    /**
     * @param runner $runner
     * @return $this
     */
    public function addToRunner(runner $runner)
    {
        $runner->addExtension($this);

        return $this;
    }

    /**
     * @param test $test
     *
     * @return $this
     */
    public function setTest(test $test)
    {
        return $this;
    }

    /**
     * @param $event
     *
     * @param observable $observable
     */
    public function handleEvent($event, observable $observable) {}
}
