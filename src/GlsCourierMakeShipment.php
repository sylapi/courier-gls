<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierMakeShipment;
use Sylapi\Courier\Contracts\Shipment;

class GlsCourierMakeShipment implements CourierMakeShipment
{
    public function makeShipment(): Shipment
    {
        return new GlsShipment();
    }
}
