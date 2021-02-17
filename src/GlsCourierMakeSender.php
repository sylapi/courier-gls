<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Gls\GlsSender;
use Sylapi\Courier\Contracts\Sender;
use Sylapi\Courier\Contracts\CourierMakeSender;

class GlsCourierMakeSender implements CourierMakeSender
{
	public function makeSender(): Sender
	{
		return new GlsSender();
	}
}
