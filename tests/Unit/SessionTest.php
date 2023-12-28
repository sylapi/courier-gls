<?php

namespace Sylapi\Courier\Gls\Tests\Unit;

use Sylapi\Courier\Gls\Session;
use Sylapi\Courier\Gls\Parameters;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;


class SessionTest extends PHPUnitTestCase
{
    public function testGlsSessionParameters()
    {
        $glsSession = new Session(Parameters::create([]));
        $this->assertInstanceOf(Parameters::class, $glsSession->parameters());
    }
}
