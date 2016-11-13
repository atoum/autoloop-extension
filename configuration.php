<?php

if (defined('mageekguy\atoum\scripts\runner') === true && version_compare(constant('mageekguy\atoum\version'), '2.9.0-beta', '>=') === true) {
    \mageekguy\atoum\scripts\runner::addConfigurationCallable(function($script, $runner) {
        $runner->addExtension(new \mageekguy\atoum\autoloop\extension($script));
    });
}
