<?php

namespace Sylapi\Courier\Gls\Tests;

use SoapFault;
use Sylapi\Courier\Gls\GlsBooking;
use Sylapi\Courier\Gls\GlsSession;
use Sylapi\Courier\Gls\GlsParameters;
use Sylapi\Courier\Gls\GlsCourierPostShipment;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class GlsCourierPostShipmentTest extends PHPUnitTestCase
{    
    private $soapMock = null;
    private $sessionMock = null;

    public function setUp(): void
    {
        $this->soapMock = $this->getSoapMock();
        $this->sessionMock = $this->getSessionMock($this->soapMock);
    }

    private function getSoapMock()
    {
        return $this->getMockBuilder('SoapClient')
                    ->disableOriginalConstructor()
                    ->getMock();
    }


    private function getSessionMock($soapMock)
    {
        $sessionMock = $this->createMock(GlsSession::class);
        $sessionMock->method('client')
            ->willReturn($soapMock);
        $sessionMock->method('token')
            ->willReturn('522a034bc583c200ebb67f51f9e242cb371d9fbcc0ab0a099e6358e078a690a2');
        $sessionMock->method('parameters')
            ->willReturn(GlsParameters::create([
                'labelType' => 'one_label_on_a4_lt_pdf'
            ]));

        return $sessionMock;
    }

    public function testPostShipmentSuccess()
    {
        $localXml =  simplexml_load_string(file_get_contents(__DIR__ . '/Mock/adePreparingBox_InsertSuccess.xml'));
        $this->soapMock->expects($this->any())->method('__call')->will($this->returnValue($localXml));
        $glsCourierGetLabels = new GlsCourierPostShipment($this->sessionMock);

        $shippingId = (string) rand(1000000,9999999);
        
        $bookingMock = $this->createMock(GlsBooking::class);
        $bookingMock->method('getShipmentId')->willReturn($shippingId);

        $response =  $glsCourierGetLabels->postShipment($bookingMock);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('shipmentId', $response);
        $this->assertNotEmpty($response['shipmentId']);
        $this->assertNotEquals($response['shipmentId'], $shippingId);
    }

    public function testPostShipmentFailure()
    {
        $shippingId = (string) rand(1000000,9999999);
        $errorCode = 'err_pickup_make_problem';
        $errorMessage = 'Error';

        $this->soapMock
                ->expects($this->any())
                ->method('__call')
                ->will($this->throwException(
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