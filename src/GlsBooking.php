<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Abstracts\Booking;

class GlsBooking extends Booking
{
    public function validate(): bool
    {
        return true;
    }
}
