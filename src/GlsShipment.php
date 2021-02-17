<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Abstracts\Shipment;

class GlsShipment extends Shipment
{
    public function getQuantity(): int
    {
        return 1;
    }

    public function validate(): bool
    {
        return true;
    }
}