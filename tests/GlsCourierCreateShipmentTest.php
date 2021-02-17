<?php

namespace Sylapi\Courier\Gls\Tests;

use SoapFault;
use Sylapi\Courier\Gls\GlsParcel;
use Sylapi\Courier\Gls\GlsSender;
use Sylapi\Courier\Gls\GlsSession;
use Sylapi\Courier\Gls\GlsReceiver;
use Sylapi\Courier\Gls\GlsShipment;
use Sylapi\Courier\Gls\GlsParameters;
use Sylapi\Courier\Gls\GlsCourierCreateShipment;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class GlsCourierCreateShipmentTest extends PHPUnitTestCase
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
        $localXml =  simplexml_load_string(file_get_contents(__DIR__ . '/Mock/adePreparingBox_InsertSuccess.xml'));
        $this->soapMock->expects($this->any())->method('__call')->will($this->returnValue($localXml));
        $glsCourierCreateShipment = new GlsCourierCreateShipment($this->sessionMock);
    
        $response =  $glsCourierCreateShipment->createShipment($this->getShipmentMock());

        $this->assertIsArray($response);
        $this->assertArrayHasKey('shipmentId', $response);
        $this->assertNotEmpty($response['shipmentId']);
    }

    public function testCreateShipmentFailure()
    {
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

        $glsCourierCreateShipment = new GlsCourierCreateShipment($this->sessionMock);

        $response =  $glsCourierCreateShipment->createShipment($this->getShipmentMock());

        $this->assertIsArray($response);
        $this->assertArrayHasKey('error', $response);
        $this->assertArrayHasKey('code', $response);
        $this->assertEquals($response['error'], $errorMessage);
        $this->assertEquals($response['code'], $errorCode);
    }

    
}