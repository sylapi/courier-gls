<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Contracts\CourierPostShipment;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Entities\Response;
use Sylapi\Courier\Exceptions\TransportException;

class GlsCourierPostShipment implements CourierPostShipment
{
    private $session;

    public function __construct(GlsSession $session)
    {
        $this->session = $session;
    }

    public function postShipment(Booking $booking): ResponseContract
    {
        $client = $this->session->client();
        $token = $this->session->token();

        $params = [
            'session'      => $token,
            'consigns_ids' => [$booking->getShipmentId()],
            'desc'         => '',
        ];

        $response = new Response();

        try {
            $result = $client->adePickup_Create($params);
            $response->shipmentId = $result->return->id;
            return $response;
        } catch (\SoapFault $fault) {
            $response->addError(
                new TransportException($fault->faultstring .' Code: '. $fault->faultcode)
            );
            return $response;
        }
    }
}
