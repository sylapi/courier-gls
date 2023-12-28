<?php

namespace Sylapi\Courier\Gls\Tests\Integration;

use SoapFault;
use Sylapi\Courier\Contracts\Response;
use Sylapi\Courier\Gls\Entities\Booking;
use Sylapi\Courier\Gls\CourierPostShipment;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Gls\Tests\Helpers\GlsSessionTrait;

class CourierPostShipmentTest extends PHPUnitTestCase
{
    use GlsSessionTrait;

    private $soapMock = null;
    private $sessionMock = null;

    public function setUp(): void
    {
        $this->soapMock = $this->getSoapMock();
        $this->sessionMock = $this->getSessionMock($this->soapMock);
    }

    public function getBookingMock($shipmentId)
    {
        $bookingMock = $this->createMock(Booking::class);
        $bookingMock->method('getShipmentId')->willReturn($shipmentId);
        $bookingMock->method('validate')->willReturn(true);

        return $bookingMock;
    }

    public function testPostShipmentSuccess()
    {
        $localXml = simplexml_load_string(file_get_contents(__DIR__.'/Mock/adePreparingBox_InsertSuccess.xml'));
        $this->soapMock->expects($this->any())->method('__call')->will($this->returnValue($localXml));
        $glsCourierPostShipment = new CourierPostShipment($this->sessionMock);

        $shipmentId = (string) rand(1000000, 9999999);
        $bookingMock = $this->getBookingMock($shipmentId);

        $response = $glsCourierPostShipment->postShipment($bookingMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertObjectHasAttribute('shipmentId', $response);
        $this->assertNotEmpty($response->shipmentId);
        $this->assertNotEquals($response->shipmentId, $shipmentId);
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

        $shipmentId = (string) rand(1000000, 9999999);
        $bookingMock = $this->getBookingMock($shipmentId);

        $glsCourierPostShipment = new CourierPostShipment($this->sessionMock);

        $response = $glsCourierPostShipment->postShipment($bookingMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->hasErrors());
    }
}
