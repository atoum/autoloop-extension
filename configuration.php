<?php

use atoum\atoum;
use atoum\atoum\scripts;

if (defined('atoum\atoum\scripts\runner') === true) {
    scripts\runner::addConfigurationCallable(function(atoum\configurator $script, atoum\runner $runner) {
        $extension = new atoum\autoloop\extension($script);

        $extension->addToRunner($runner);
    });
}
