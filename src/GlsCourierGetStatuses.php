<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierGetStatuses;
use Sylapi\Courier\Enums\StatusType;

class GlsCourierGetStatuses implements CourierGetStatuses
{
    private $session;

    public function __construct(GlsSession $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $shipmentId): string
    {
        return StatusType::APP_UNAVAILABLE;
    }
}
