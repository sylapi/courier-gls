<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Contracts\CourierMakeBooking;

class GlsCourierMakeBooking implements CourierMakeBooking
{
	public function makeBooking() : Booking
	{
		return new GlsBooking();
	}
}
