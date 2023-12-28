<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Contracts\CourierMakeBooking as CourierMakeBookingContract;

class CourierMakeBooking implements CourierMakeBookingContract
{
    public function makeBooking(): Booking
    {
        return new Booking();
    }
}
