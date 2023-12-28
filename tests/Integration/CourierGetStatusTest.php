<?php

namespace Sylapi\Courier\Gls\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Gls\CourierGetStatuses;
use Sylapi\Courier\Gls\Tests\Helpers\GlsSessionTrait;

class CourierGetStatusTest extends PHPUnitTestCase
{
    use GlsSessionTrait;

    private $soapMock = null;
    private $sessionMock = null;

    public function setUp(): void
    {
        $this->soapMock = $this->getSoapMock();
        $this->sessionMock = $this->getSessionMock($this->soapMock);
    }

    public function testGetStatusSuccess()
    {
        $localXml = simplexml_load_string(file_get_contents(__DIR__.'/Mock/default.xml'));
        $this->soapMock->expects($this->any())->method('__call')->will($this->returnValue($localXml));
        $glsCourierGetStatuses = new CourierGetStatuses($this->sessionMock);

        $shippingId = (string) rand(1000000, 9999999);
        $response = $glsCourierGetStatuses->getStatus($shippingId);

        $this->assertEquals(StatusType::APP_UNAVAILABLE, $response);
    }
}
