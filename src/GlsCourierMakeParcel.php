<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\Parcel;
use Sylapi\Courier\Contracts\CourierMakeParcel;

class GlsCourierMakeParcel implements CourierMakeParcel
{
	public function makeParcel() : Parcel
	{
		return new GlsParcel();
	}
}
