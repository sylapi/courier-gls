<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Contracts\CourierPostShipment as CourierPostShipmentContract;
use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Entities\Response;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Gls\Helpers\GlsValidateErrorsHelper;
use Sylapi\Courier\Helpers\ResponseHelper;

class CourierPostShipment implements CourierPostShipmentContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function postShipment(Booking $booking): ResponseContract
    {
        $response = new Response();

        if (!$booking->validate()) {
            $errors = GlsValidateErrorsHelper::toArrayExceptions($response->getErrors());
            ResponseHelper::pushErrorsToResponse($response, $errors);

            return $response;
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
            $response->shipmentId = $result->return->id;

            return $response;
        } catch (\SoapFault $fault) {
            $error = new TransportException($fault->faultstring.' Code: '.$fault->faultcode);
            ResponseHelper::pushErrorsToResponse($response, [$error]);

            return $response;
        }
    }
}
