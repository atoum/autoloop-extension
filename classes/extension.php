<?php

namespace mageekguy\atoum\autoloop;

use mageekguy\atoum;
use mageekguy\atoum\observable;
use mageekguy\atoum\runner;
use mageekguy\atoum\test;

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

            $autoLoopHandler = function(\mageekguy\atoum\scripts\runner $script, $argument, $values) use ($configuration) {
                $script->enableLoopMode();

                $customPrompt = new prompt();
                $customPrompt->setOutputWriter($script->getPrompt()->getOutputWriter());
                $customPrompt->setInputReader($script->getPrompt()->getInputReader());
                $customPrompt->setRunner($script->getRunner());
                $customPrompt->setConfiguration($configuration);
                $script->setPrompt($customPrompt);

            };

            $script
                ->addArgumentHandler(
                    $autoLoopHandler,
                    array('--autoloop'),
                    null,
                    $script->getLocale()->_('Automaticly relaunch tests on file change (implies --loop)')
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
    public function addToRunner(\mageekguy\atoum\runner $runner)
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
