<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Entities\Response;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Helpers\ResponseHelper;
use Sylapi\Courier\Exceptions\ValidateException;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Gls\Helpers\ValidateErrorsHelper;
use Sylapi\Courier\Gls\Helpers\GlsValidateErrorsHelper;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Gls\Entities\Shipment as ShipmentEntity;
use Sylapi\Courier\Gls\Responses\Shipment as ShipmentResponse;
use Sylapi\Courier\Contracts\CourierCreateShipment as CourierCreateShipmentContract;

class CourierCreateShipment implements CourierCreateShipmentContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function createShipment(Shipment $shipment): ResponseContract
    {
        $response = new ShipmentResponse();
        
        /**
         * @var ShipmentEntity $shipment
         */
        if (!$shipment->validate()) {
            throw new ValidateException('Invalid Shipment: ' . ValidateErrorsHelper::getError($shipment->getErrors()));
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
            $response->setResponse($result);
            $response->setShipmentId((string) $result->return->id);
        } catch (\SoapFault $fault) {
            throw new TransportException($fault->faultstring.' Code: '.$fault->faultcode);
        }

        return $response;
    }

    private function getConsign(ShipmentEntity $shipment): array
    {
        // $parameters = $this->session->parameters();

        $consign = [
            'rname1'     => $shipment->getReceiver()->getFirstName(),
            'rname2'     => $shipment->getReceiver()->getSurname(),
            'rcountry'   => $shipment->getReceiver()->getCountryCode(),
            'rzipcode'   => $shipment->getReceiver()->getZipCode(),
            'rcity'      => $shipment->getReceiver()->getCity(),
            'rstreet'    => $shipment->getReceiver()->getStreet(),
            'rphone'     => $shipment->getReceiver()->getPhone(),
            'rcontact'   => $shipment->getReceiver()->getEmail(),
            // 'date'       => $parameters->postDate ?? date('Y-m-d'), //TODO
            'references' => $shipment->getContent(),
            'notes'      => $shipment->getNotes(),
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

        //TODO: services
        /*
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
                'rcountry' => $shipment->getReceiver()->getCountryCode(),
                'rzipcode' => $shipment->getReceiver()->getZipCode(),
                'rcity'    => $shipment->getReceiver()->getCity(),
                'rstreet'  => $shipment->getReceiver()->getStreet(),
                'rphone'   => $shipment->getReceiver()->getPhone(),

                'references' => $shipment->getContent(),
                'weight'     => $shipment->getParcel()->getWeight(),
            ];
        }
        */

        return $consign;
    }
}
