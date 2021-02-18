<?php

namespace Sylapi\Courier\Gls\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use SoapFault;
use Sylapi\Courier\Gls\GlsCourierCreateShipment;
use Sylapi\Courier\Gls\GlsParcel;
use Sylapi\Courier\Gls\GlsReceiver;
use Sylapi\Courier\Gls\GlsSender;
use Sylapi\Courier\Gls\GlsShipment;
use Sylapi\Courier\Gls\Tests\Helpers\GlsSessionTrait;
use Sylapi\Courier\Contracts\Response;

class GlsCourierCreateShipmentTest extends PHPUnitTestCase
{
    use GlsSessionTrait;

    private $soapMock = null;
    private $sessionMock = null;

    public function setUp(): void
    {
        $this->soapMock = $this->getSoapMock();
        $this->sessionMock = $this->getSessionMock($this->soapMock);
    }

    private function getShipmentMock()
    {
        $senderMock = $this->createMock(GlsSender::class);
        $receiverMock = $this->createMock(GlsReceiver::class);
        $parcelMock = $this->createMock(GlsParcel::class);
        $shipmentMock = $this->createMock(GlsShipment::class);

        $shipmentMock->method('getSender')
                ->willReturn($senderMock);

        $shipmentMock->method('getReceiver')
                ->willReturn($receiverMock);

        $shipmentMock->method('getParcel')
                ->willReturn($parcelMock);

        return $shipmentMock;
    }

    public function testCreateShipmentSuccess()
    {
        $localXml = simplexml_load_string(file_get_contents(__DIR__.'/Mock/adePreparingBox_InsertSuccess.xml'));
        $this->soapMock->expects($this->any())->method('__call')->will($this->returnValue($localXml));
        $glsCourierCreateShipment = new GlsCourierCreateShipment($this->sessionMock);

        $response = $glsCourierCreateShipment->createShipment($this->getShipmentMock());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertObjectHasAttribute('shipmentId', $response);
        $this->assertNotEmpty($response->shipmentId);
    }

    public function testCreateShipmentFailure()
    {
        $errorCode = 'err_pickup_make_problem';
        $errorMessage = 'Error';

        $this->soapMock
                ->expects($this->any())
                ->method('__call')
                ->will(
                    $this->throwException(
                        new SoapFault(
                        $errorCode,
                        $errorMessage
                    )
                    )
                );

        $glsCourierCreateShipment = new GlsCourierCreateShipment($this->sessionMock);

        $response = $glsCourierCreateShipment->createShipment($this->getShipmentMock());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->hasErrors());
    }
}
