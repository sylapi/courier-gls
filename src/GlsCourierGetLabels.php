<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierGetLabels;
use Sylapi\Courier\Contracts\Label as LabelContract;
use Sylapi\Courier\Entities\Label;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Helpers\ResponseHelper;

class GlsCourierGetLabels implements CourierGetLabels
{
    private $session;

    public function __construct(GlsSession $session)
    {
        $this->session = $session;
    }

    public function getLabel(string $shipmentId): LabelContract
    {
        $client = $this->session->client();
        $parameters = $this->session->parameters();
        $token = $this->session->token();

        try {
            $params = [
                'session' => $token,
                'id'      => $shipmentId,
                'mode'    => $parameters->getLabelType(),
            ];

            $result = $client->adePickup_GetLabels($params);

            return new Label((string) $result->return->labels);
        } catch (\SoapFault $fault) {
            $label = new Label(null);
            $excaption = new TransportException($fault->faultstring.' Code: '.$fault->faultcode);
            ResponseHelper::pushErrorsToResponse($label, [$excaption]);

            return $label;
        }
    }
}
