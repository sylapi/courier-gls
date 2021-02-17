<?php

namespace Sylapi\Courier\Gls\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Gls\GlsParameters;
use Sylapi\Courier\Gls\GlsSession;

class GlsSessionTest extends PHPUnitTestCase
{
    public function testGlsSessionParameters()
    {
        $glsSession = new GlsSession(GlsParameters::create([]));
        $this->assertInstanceOf(GlsParameters::class, $glsSession->parameters());
    }
}
