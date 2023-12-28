<?php

namespace Sylapi\Courier\Gls\Tests;

use SoapFault;
use Sylapi\Courier\Gls\Responses\Shipment as ResponsesShipment;
use Sylapi\Courier\Gls\Entities\Parcel;
use Sylapi\Courier\Gls\Entities\Sender;
use Sylapi\Courier\Gls\Entities\Receiver;
use Sylapi\Courier\Gls\Entities\Shipment;
use Sylapi\Courier\Gls\CourierCreateShipment;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Gls\Tests\Helpers\SessionTrait;


class CourierCreateShipmentTest extends PHPUnitTestCase
{
    use SessionTrait;

    private $soapMock = null;
    private $sessionMock = null;

    public function setUp(): void
    {
        $this->soapMock = $this->getSoapMock();
        $this->sessionMock = $this->getSessionMock($this->soapMock);
    }

    private function getShipmentMock()
    {
        $senderMock = $this->createMock(Sender::class);
        $receiverMock = $this->createMock(Receiver::class);
        $parcelMock = $this->createMock(Parcel::class);
        $shipmentMock = $this->createMock(Shipment::class);

        $shipmentMock->method('getSender')
                ->willReturn($senderMock);

        $shipmentMock->method('getReceiver')
                ->willReturn($receiverMock);

        $shipmentMock->method('getParcel')
                ->willReturn($parcelMock);

        $shipmentMock->method('validate')
                ->willReturn(true);

        return $shipmentMock;
    }

    public function testCreateShipmentSuccess()
    {
        $localXml = simplexml_load_string(file_get_contents(__DIR__.'/Mock/adePreparingBox_InsertSuccess.xml'));
        $this->soapMock->expects($this->any())->method('__call')->will($this->returnValue($localXml));
        $glsCourierCreateShipment = new CourierCreateShipment($this->sessionMock);

        $response = $glsCourierCreateShipment->createShipment($this->getShipmentMock());

        $this->assertInstanceOf(ResponsesShipment::class, $response);
        $this->assertNotEmpty($response->getShipmentId());
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

        $glsCourierCreateShipment = new CourierCreateShipment($this->sessionMock);

        $glsCourierCreateShipment->createShipment($this->getShipmentMock());
        $this->expectException(TransportException::class);
    }
}
