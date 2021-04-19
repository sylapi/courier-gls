<?php

namespace Sylapi\Courier\Gls;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class GetLabelTest extends PHPUnitTestCase
{
    private $gls = null;
    private $soapMock = null;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->soapMock = $this->getMockBuilder('SoapClient')->disableOriginalConstructor()->getMock();

        $this->gls = new Gls();
        $this->gls->setSession('123456abcd');

        $params = [
            'accessData' => [
                'login'    => 'login',
                'password' => 'password',
            ],
            'tracking_id' => '123456',
            'format'      => 'A6',
        ];

        $this->gls->initialize($params);
    }

    public function testGetLabelSuccess()
    {
        $localXml = file_get_contents(__DIR__.'/Mock/adePickup_GetParcelLabelSuccess.xml');

        $this->soapMock->expects($this->any())->method('__call')->will(
            $this->returnValue(
                simplexml_load_string($localXml, 'SimpleXMLElement', LIBXML_NOCDATA)
            )
        );

        $this->gls->setSoapClient($this->soapMock);
        $this->gls->GetLabel();

        $this->assertNull($this->gls->getError());
        $this->assertTrue($this->gls->isSuccess());
        $this->assertNotNull($this->gls->getResponse());
    }

    public function testGetLabelFailure()
    {
        $localXml = file_get_contents(__DIR__.'/Mock/adePickup_GetParcelLabelFailure.xml');

        $this->soapMock->expects($this->any())->method('__call')->will(
            $this->returnValue(
                simplexml_load_string($localXml, 'SimpleXMLElement', LIBXML_NOCDATA)
            )
        );

        $this->gls->setSoapClient($this->soapMock);
        $this->gls->GetLabel();

        $this->assertNotNull($this->gls->getError());
        $this->assertFalse($this->gls->isSuccess());
        $this->assertNull($this->gls->getResponse());
    }
}
