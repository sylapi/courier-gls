<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierCreateShipment;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Entities\Response;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Gls\Helpers\GlsValidateErrorsHelper;
use Sylapi\Courier\Helpers\ResponseHelper;

class GlsCourierCreateShipment implements CourierCreateShipment
{
    private $session;

    public function __construct(GlsSession $session)
    {
        $this->session = $session;
    }

    public function createShipment(Shipment $shipment): ResponseContract
    {
        $response = new Response();

        if (!$shipment->validate()) {
            $errors = GlsValidateErrorsHelper::toArrayExceptions($shipment->getErrors());
            ResponseHelper::pushErrorsToResponse($response, $errors);

            return $response;
        }

        $client = $this->session->client();
        $token = $this->session->token();
        $consign = $this->getConsign($shipment);

        $params = [
            'session'           => $token,
            'consign_prep_data' => $consign,
        ];

        try {
            $result = $client->adePreparingBox_Insert($params);
            $response->shipmentId = $result->return->id;
        } catch (\SoapFault $fault) {
            $excaption = new TransportException($fault->faultstring.' Code: '.$fault->faultcode);
            ResponseHelper::pushErrorsToResponse($response, [$excaption]);
        }

        return $response;
    }

    private function getConsign(Shipment $shipment): array
    {
        $parameters = $this->session->parameters();

        $consign = [
            'rname1'     => $shipment->getReceiver()->getFirstName(),
            'rname2'     => $shipment->getReceiver()->getSurname(),
            'rcountry'   => $shipment->getReceiver()->getCountryCode(),
            'rzipcode'   => $shipment->getReceiver()->getZipCode(),
            'rcity'      => $shipment->getReceiver()->getCity(),
            'rstreet'    => $shipment->getReceiver()->getStreet(),
            'rphone'     => $shipment->getReceiver()->getPhone(),
            'rcontact'   => $shipment->getReceiver()->getEmail(),
            'date'       => $parameters->postDate ?? date('Y-m-d'),
            'references' => $shipment->getContent(),
            'notes' => $shipment->getNotes(),
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

        if (isset($parameters->services) && is_array($parameters->services)) {
            $consign['srv_bool'] = $parameters->services;
        }

        if (isset($parameters->services)
            && is_array($parameters->services)
            && isset($parameters->services['srs'])
        ) {
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
                'rcountry' => $shipment->getReceiver()->getCountry(),
                'rzipcode' => $shipment->getReceiver()->getZipCode(),
                'rcity'    => $shipment->getReceiver()->getCity(),
                'rstreet'  => $shipment->getReceiver()->getCity(),
                'rphone'   => $shipment->getReceiver()->getPhone(),

                'references' => $shipment->getContent(),
                'weight'     => $shipment->getParcel()->getWeight(),
            ];
        }

        return $consign;
    }
}
