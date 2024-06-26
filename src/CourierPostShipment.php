<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Gls\Responses\Parcel as ParcelResponse;
use Sylapi\Courier\Exceptions\ValidateException;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Gls\Helpers\ValidateErrorsHelper;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Contracts\CourierPostShipment as CourierPostShipmentContract;
use Sylapi\Courier\Responses\Parcel as ResponseParcel;

class CourierPostShipment implements CourierPostShipmentContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function postShipment(Booking $booking): ResponseParcel
    {
        $response = new ParcelResponse();

        if (!$booking->validate()) {
            throw new ValidateException('Invalid Shipment: ' . ValidateErrorsHelper::getError($booking->getErrors()));
        }

        $client = $this->session->client();
        $token = $this->session->token();

        $params = [
            'session'      => $token,
            'consigns_ids' => [$booking->getShipmentId()],
            'desc'         => '',
        ];

        try {
            $result = $client->adePickup_Create($params);
            $response->setResponse($result);
            $response->setShipmentId((string) $result->return->id);

            return $response;
        } catch (\SoapFault $fault) {
            throw new TransportException($fault->faultstring.' Code: '.$fault->faultcode);
        }
    }
}
