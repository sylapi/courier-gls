<?php

namespace Sylapi\Courier\Gls\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use SoapFault;
use Sylapi\Courier\Gls\GlsBooking;
use Sylapi\Courier\Gls\GlsCourierPostShipment;
use Sylapi\Courier\Gls\Tests\Helpers\GlsSessionTrait;

class GlsCourierPostShipmentTest extends PHPUnitTestCase
{
    use GlsSessionTrait;

    private $soapMock = null;
    private $sessionMock = null;

    public function setUp(): void
    {
        $this->soapMock = $this->getSoapMock();
        $this->sessionMock = $this->getSessionMock($this->soapMock);
    }

    public function testPostShipmentSuccess()
    {
        $localXml = simplexml_load_string(file_get_contents(__DIR__.'/Mock/adePreparingBox_InsertSuccess.xml'));
        $this->soapMock->expects($this->any())->method('__call')->will($this->returnValue($localXml));
        $glsCourierPostShipment = new GlsCourierPostShipment($this->sessionMock);

        $shippingId = (string) rand(1000000, 9999999);

        $bookingMock = $this->createMock(GlsBooking::class);
        $bookingMock->method('getShipmentId')->willReturn($shippingId);

        $response = $glsCourierPostShipment->postShipment($bookingMock);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('shipmentId', $response);
        $this->assertNotEmpty($response['shipmentId']);
        $this->assertNotEquals($response['shipmentId'], $shippingId);
    }

    public function testPostShipmentFailure()
    {
        $shippingId = (string) rand(1000000, 9999999);
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

        $bookingMock = $this->createMock(GlsBooking::class);
        $bookingMock->method('getShipmentId')->willReturn($shippingId);

        $glsCourierPostShipment = new GlsCourierPostShipment($this->sessionMock);

        $response = $glsCourierPostShipment->postShipment($bookingMock);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('error', $response);
        $this->assertArrayHasKey('code', $response);
        $this->assertEquals($response['error'], $errorMessage);
        $this->assertEquals($response['code'], $errorCode);
    }
}
