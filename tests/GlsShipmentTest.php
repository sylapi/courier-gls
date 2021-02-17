<?php

namespace Sylapi\Courier\Gls\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Gls\GlsParcel;
use Sylapi\Courier\Gls\GlsReceiver;
use Sylapi\Courier\Gls\GlsSender;
use Sylapi\Courier\Gls\GlsShipment;

class GlsShipmentTest extends PHPUnitTestCase
{
    public function testNumberOfPackagesIsAlwaysEqualTo1()
    {
        $parcel = new GlsParcel();
        $shipment = new GlsShipment();
        $shipment->setParcel($parcel);
        $shipment->setParcel($parcel);

        $this->assertEquals(1, $shipment->getQuantity());
    }

    public function testShipmentValidate()
    {
        $receiver = new GlsReceiver();
        $sender = new GlsSender();
        $parcel = new GlsParcel();

        $shipment = new GlsShipment();
        $shipment->setSender($sender)
            ->setReceiver($receiver)
            ->setParcel($parcel);

        $this->assertIsBool($shipment->validate());
        $this->assertIsBool($shipment->getReceiver()->validate());
        $this->assertIsBool($shipment->getSender()->validate());
        $this->assertIsBool($shipment->getParcel()->validate());
    }
}
