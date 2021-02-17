<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierGetLabels;

class GlsCourierGetLabels implements CourierGetLabels
{
    private $session;

    public function __construct(GlsSession $session)
    {
        $this->session = $session;
    }

    public function getLabel(string $shipmentId): ?string
    {
        // var_dump('Gls::GetLabel');

        $client = $this->session->client();
        $parameters = $this->session->parameters();
        $token = $this->session->token();

        $response = null;

        try {
            $params = [
                'session' => $token,
                'id'      => $shipmentId,
                'mode'    => $parameters->getLabelType(),
            ];

            $result = $client->adePickup_GetLabels($params);
            $response = (string) $result->return->labels;
        } catch (\SoapFault $fault) {
            // $response = [
            // 	'error' => $fault->faultstring,
            // 	'code' => $fault->faultcode
            // ];
            $response = null;
        }

        return $response;
    }
}
