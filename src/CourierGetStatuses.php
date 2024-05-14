<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierGetStatuses as CourierGetStatusesContract;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Gls\Responses\Status as StatusResponse;
use Sylapi\Courier\Responses\Status as ResponseStatus;

class CourierGetStatuses implements CourierGetStatusesContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $shipmentId): ResponseStatus
    {
        $client = $this->session->clientTracking();
        $credentials = $this->session->credentials();

        $payload = array(
            'Credentials' => array('UserName' => $credentials->getTrackingLogin(), 'Password' => $credentials->getTrackingPassword()),
            'RefValue' => $shipmentId
        );
        
        $result = $client->GetTuDetail($payload);

        $histories = $result->History ?? null;
        
        if($histories === null || count($histories) === 0) {
            throw new TransportException('History is available');
        }

        $history = reset($histories);

        $statusResponse = new StatusResponse((string) new StatusTransformer($history->Code), $history->Desc ?? 'Original status description not found');
        $statusResponse->setResponse($result);
        $statusResponse->setRequest($payload);

        return $statusResponse;
    }
}
