<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierGetStatuses;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Contracts\Status as StatusContract;
use Sylapi\Courier\Entities\Status;
use Sylapi\Courier\Exceptions\UnavailableMethodException;

class GlsCourierGetStatuses implements CourierGetStatuses
{
    private $session;

    public function __construct(GlsSession $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $shipmentId): StatusContract
    {
        $status = new Status(StatusType::APP_UNAVAILABLE);
        $status->addError(new UnavailableMethodException('This service is not available for this courier
        '));
        return $status;
    }
}
