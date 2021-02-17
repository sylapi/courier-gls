<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Gls\GlsReceiver;
use Sylapi\Courier\Contracts\Receiver;
use Sylapi\Courier\Contracts\CourierMakeReceiver;

class GlsCourierMakeReceiver implements CourierMakeReceiver
{
	public function makeReceiver(): Receiver
	{
		return new GlsReceiver();
	}
}
