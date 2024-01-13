<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Gls\Services\Srs;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Gls\Entities\Options;
use Sylapi\Courier\Exceptions\ValidateException;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Gls\Helpers\ValidateErrorsHelper;
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
        /**
         * @var Options $options
         */
        $options = $shipment->getOptions();

        $consign = [
            'rname1'     => $shipment->getReceiver()->getFirstName(),
            'rname2'     => $shipment->getReceiver()->getSurname(),
            'rcountry'   => $shipment->getReceiver()->getCountryCode(),
            'rzipcode'   => $shipment->getReceiver()->getZipCode(),
            'rcity'      => $shipment->getReceiver()->getCity(),
            'rstreet'    => $shipment->getReceiver()->getStreet(),
            'rphone'     => $shipment->getReceiver()->getPhone(),
            'rcontact'   => $shipment->getReceiver()->getEmail(),
            'date'       => $options->getPostDate(),
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

        $services = $shipment->getServices();
        
        if($services) {
            foreach($services as $service) {
                $service->setRequest($consign);

                if($service instanceof Srs) {
                    $service->setShipment($shipment);
                } 
                $consign = $service->handle();
            }
        } 


        return $consign;
    }
}
