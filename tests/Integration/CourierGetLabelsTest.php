<?php

namespace Sylapi\Courier\Gls\Tests;

use SoapFault;
use Sylapi\Courier\Contracts\Response;
use Sylapi\Courier\Gls\CourierGetLabels;
use Sylapi\Courier\Gls\Entities\LabelType;
use Sylapi\Courier\Exceptions\TransportException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Gls\Tests\Helpers\SessionTrait;

class CourierGetLabelsTest extends PHPUnitTestCase
{
    use SessionTrait;

    private $soapMock = null;
    private $sessionMock = null;

    public function setUp(): void
    {
        $this->soapMock = $this->getSoapMock();
        $this->sessionMock = $this->getSessionMock($this->soapMock);
    }

    public function testGetLabelSuccess()
    {
        $localXml = simplexml_load_string(file_get_contents(__DIR__.'/Mock/adePickup_GetLabelsSuccess.xml'));
        $this->soapMock->expects($this->any())->method('__call')->will($this->returnValue($localXml));
        $glsCourierGetLabels = new CourierGetLabels($this->sessionMock);
        $labelTypeMock = $this->createMock(LabelType::class);
        $this->assertEquals(
            $glsCourierGetLabels->getLabel((string) rand(1000000, 9999999), $labelTypeMock),
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

        $this->expectException(TransportException::class);
        $glsCourierGetLabels = new CourierGetLabels($this->sessionMock);
        $labelTypeMock = $this->createMock(LabelType::class);
        $glsCourierGetLabels->getLabel($shippingId, $labelTypeMock);
    }
}
