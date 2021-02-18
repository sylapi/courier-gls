<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierCreateShipment;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Entities\Response;
use Sylapi\Courier\Exceptions\TransportException;

class GlsCourierCreateShipment implements CourierCreateShipment
{
    private $session;

    public function __construct(GlsSession $session)
    {
        $this->session = $session;
    }

    public function createShipment(Shipment $shipment): ResponseContract
    {
        $client = $this->session->client();
        $token = $this->session->token();
        $consign = $this->getConsign($shipment);

        $params = [
            'session'           => $token,
            'consign_prep_data' => $consign,
        ];

        $response = new Response();

        try {
            $result = $client->adePreparingBox_Insert($params);
            $response->shipmentId = $result->return->id;
        } catch (\SoapFault $fault) {
            $response->addError(
                new TransportException($fault->faultstring .' Code: '. $fault->faultcode)
            );
        }

        return $response;
    }

    private function getConsign(Shipment $shipment): array
    {
        $consign = [
            'rname1'     => $shipment->getReceiver()->getFirstName(),
            'rname2'     => $shipment->getReceiver()->getSurname(),
            'rcountry'   => $shipment->getReceiver()->getCountryCode(),
            'rzipcode'   => $shipment->getReceiver()->getZipCode(),
            'rcity'      => $shipment->getReceiver()->getCity(),
            'rstreet'    => $shipment->getReceiver()->getStreet(),
            'rphone'     => $shipment->getReceiver()->getPhone(),
            'rcontact'   => $shipment->getReceiver()->getEmail(),
            'references' => $shipment->getContent(),
            'sendaddr'   => [
                'name1'   => $shipment->getSender()->getFullName(),
                'name2'   => '',
                'name3'   => '',
                'country' => $shipment->getSender()->getCountryCode(),
                'zipcode' => $shipment->getSender()->getZipCode(),
                'city'    => $shipment->getSender()->getCity(),
                'street'  => $shipment->getSender()->getStreet(),
            ],
            'parcels' => [
                [
                    'reference' => $shipment->getContent(),
                    'weight'    => $shipment->getParcel()->getWeight(),
                ],
            ],
        ];

        return $consign;
    }
}
