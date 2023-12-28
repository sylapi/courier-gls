<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierGetLabels as CourierGetLabelsContract;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Gls\Responses\Label as LabelResponse;
use Sylapi\Courier\Contracts\LabelType as LabelTypeContract;

class CourierGetLabels implements CourierGetLabelsContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getLabel(string $shipmentId, LabelTypeContract $labelType): ResponseContract
    {
        $client = $this->session->client();
        // $parameters = $this->session->parameters();
        $token = $this->session->token();

        try {
            $params = [
                'session' => $token,
                'id'      => $shipmentId,
                'mode'    => $labelType->getLabelType(),
            ];

            $result = $client->adePickup_GetLabels($params);
            return new LabelResponse((string) $result->return->labels);

        } catch (\SoapFault $fault) {
            throw new TransportException($fault->faultstring.' Code: '.$fault->faultcode);
        }
    }
}
