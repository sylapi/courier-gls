<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Courier;
use Sylapi\Courier\Gls\GlsParameters;

class GlsCourierApiFactory
{
	private $glsSessionFactory;

	public function __construct(GlsSessionFactory $glsSessionFactory)
	{
		$this->glsSessionFactory = $glsSessionFactory;
	}

	public function create(array $parameters) : Courier
	{
		$session = $this->glsSessionFactory
					->session(GlsParameters::create($parameters));
					
		return new Courier(
			new GlsCourierCreateShipment($session),
			new GlsCourierPostShipment($session),
			new GlsCourierGetLabels($session),
			new GlsCourierGetStatuses($session),
			new GlsCourierMakeShipment(),
			new GlsCourierMakeParcel(),
			new GlsCourierMakeReceiver(),
			new GlsCourierMakeSender(),
			new GlsCourierMakeBooking()
		);
	}

}
