<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierGetStatuses as CourierGetStatusesContract;
use Sylapi\Courier\Contracts\Status as StatusContract;
use Sylapi\Courier\Entities\Status;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Exceptions\UnavailableMethodException;
use Sylapi\Courier\Helpers\ResponseHelper;
use Sylapi\Courier\Contracts\Response as ResponseContract;

class CourierGetStatuses implements CourierGetStatusesContract
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $shipmentId): ResponseContract
    {
        $status = new Status(StatusType::APP_UNAVAILABLE);
        $exception = new UnavailableMethodException('This service is not available for this courier');
        ResponseHelper::pushErrorsToResponse($status, [$exception]);

        return $status;
    }
}
