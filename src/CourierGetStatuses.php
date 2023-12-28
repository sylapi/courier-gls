<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierGetStatuses as CourierGetStatusesContract;
use Sylapi\Courier\Exceptions\UnavailableMethodException;
use Sylapi\Courier\Contracts\Response as ResponseContract;

class CourierGetStatuses implements CourierGetStatusesContract
{
    /* @phpstan-ignore-next-line */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $shipmentId): ResponseContract
    {
        throw new UnavailableMethodException('Method getStatus is not available for this courier.');
    }
}
