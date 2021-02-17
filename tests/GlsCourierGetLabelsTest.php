<?php

namespace Sylapi\Courier\Gls\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use SoapFault;
use Sylapi\Courier\Gls\GlsCourierGetLabels;
use Sylapi\Courier\Gls\GlsParameters;
use Sylapi\Courier\Gls\GlsSession;

class GlsCourierGetLabelsTest extends PHPUnitTestCase
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
                'labelType' => 'one_label_on_a4_lt_pdf',
            ]));
        $sessionMock->method('parameters')
            ->willReturn(GlsParameters::create([
                'labelType' => 'one_label_on_a4_lt_pdf',
            ]));

        return $sessionMock;
    }

    public function testGetLabelSuccess()
    {
        $localXml = simplexml_load_string(file_get_contents(__DIR__.'/Mock/adePickup_GetLabelsSuccess.xml'));
        $this->soapMock->expects($this->any())->method('__call')->will($this->returnValue($localXml));
        $glsCourierGetLabels = new GlsCourierGetLabels($this->sessionMock);
        $this->assertEquals(
            $glsCourierGetLabels->getLabel((string) rand(1000000, 9999999)),
            'JVBERi0xLjMKMyAwIG9iago8PC9UeXBlIC9QYWdlCi9QYXJlbnQgMSAwIFIKL01lZGlhQm94IFswIDAgODQxLjg5IDU5NS4yOF0KL1Jlc291cmNlcyAyIDAgUgovQ29udGVudHMgNCAwIFI'
        );
    }

    public function testGetLabelFailure()
    {
        $shippingId = (string) rand(1000000, 9999999);

        $this->soapMock
                ->expects($this->any())
                ->method('__call')
                ->will(
                    $this->throwException(
                    new SoapFault(
                            'err_parcel_number_is_invalid',
                            "num: $shippingId mode:one_label_on_a4_lt_pdf"
                        )
                )
                );

        $glsCourierGetLabels = new GlsCourierGetLabels($this->sessionMock);
        $this->assertNull($glsCourierGetLabels->getLabel($shippingId));
    }
}
