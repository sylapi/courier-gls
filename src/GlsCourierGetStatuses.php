<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierGetStatuses;
use Sylapi\Courier\Contracts\Status as StatusContract;
use Sylapi\Courier\Entities\Status;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Exceptions\UnavailableMethodException;
use Sylapi\Courier\Helpers\ResponseHelper;

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
        $excaption = new UnavailableMethodException('This service is not available for this courier');
        ResponseHelper::pushErrorsToResponse($status, [$excaption]);

        return $status;
    }
}
