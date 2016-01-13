<?php

namespace mageekguy\atoum\autoloop;

use mageekguy\atoum;
use mageekguy\atoum\observable;
use mageekguy\atoum\runner;
use mageekguy\atoum\test;

class extension implements atoum\extension
{
    /**
     * @param atoum\configurator $configurator
     */
    public function __construct(atoum\configurator $configurator = null)
    {

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

            $autoLoopHandler = function(\mageekguy\atoum\scripts\runner $script, $argument, $values) {
                $script->enableLoopMode();

                $customPrompt = new prompt();
                $customPrompt->setOutputWriter($script->getPrompt()->getOutputWriter());
                $customPrompt->setInputReader($script->getPrompt()->getInputReader());
                $customPrompt->setRunner($script->getRunner());
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

    public function setRunner(runner $runner)
    {
        return $this;
    }

    public function addToRunner(\mageekguy\atoum\runner $runner)
    {
        $runner->addExtension($this, $this->configuration);

        return $this;
    }

    public function setTest(test $test)
    {
        return $this;
    }

    public function handleEvent($event, observable $observable) {}
}
