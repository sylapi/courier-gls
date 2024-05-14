<?php

namespace Sylapi\Courier\Gls\Services;

use InvalidArgumentException;
use Sylapi\Courier\Abstracts\Service;
use Sylapi\Courier\Contracts\Service as ServiceContract;
use Sylapi\Courier\Gls\Entities\Shipment;

class Srs extends Service
{
    public function getShipment(): ?Shipment
    {
        return $this->get('shipment', null);
    }

    public function setShipment(Shipment $shipment): ServiceContract
    {
        $this->set('shipment', $shipment);
        return $this;
    }

    public function handle(): array
    {
        $consign = $this->getRequest();
        $shipment = $this->getShipment();
        
        if($consign === null || !$shipment instanceof Shipment) {
            throw new InvalidArgumentException('Request or Shipment is not defined');
        }

        $consign['srv_ppe'] = [
            'sname1'   => $shipment->getSender()->getFullName(),
            'scountry' => $shipment->getSender()->getCountryCode(),
            'szipcode' => $shipment->getSender()->getZipCode(),
            'scity'    => $shipment->getSender()->getCity(),
            'sstreet'  => $shipment->getSender()->getStreet(),
            'sphone'   => $shipment->getSender()->getPhone(),

            'rname1'   => $shipment->getReceiver()->getFullName(),
            'rname2'   => '',
            'rname3'   => '',
            'rcountry' => $shipment->getReceiver()->getCountryCode(),
            'rzipcode' => $shipment->getReceiver()->getZipCode(),
            'rcity'    => $shipment->getReceiver()->getCity(),
            'rstreet'  => $shipment->getReceiver()->getStreet(),
            'rphone'   => $shipment->getReceiver()->getPhone(),

            'references' => $shipment->getContent(),
            'weight'     => $shipment->getParcel()->getWeight(),
        ];



        return $consign;
    }
}
