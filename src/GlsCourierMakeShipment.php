<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Gls\GlsShipment;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Contracts\CourierMakeShipment;

class GlsCourierMakeShipment implements CourierMakeShipment
{
	public function makeShipment() : Shipment
	{
		return new GlsShipment();
	}
}
