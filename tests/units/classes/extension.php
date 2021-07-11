<?php

namespace atoum\atoum\autoloop\tests\units;

use atoum\atoum;
use atoum\atoum\autoloop\extension as testedClass;

class extension extends atoum\test
{
    public function testSetTest()
    {
        $this
            ->if($extension = new testedClass())
                ->and($test = new \mock\atoum\atoum\test())
                ->and($manager = new \mock\atoum\atoum\test\assertion\manager())
                ->and($test->setAssertionManager($manager))
            ->then
                ->object($extension->setTest($test))->isIdenticalTo($extension)
        ;
    }
}
