<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Contracts\CourierGetStatuses;

class GlsCourierGetStatuses implements CourierGetStatuses
{
	private $session;

	public function __construct(GlsSession $session)
	{
		$this->session = $session;
	}

	public function getStatus(string $shipmentId): string
	{
		var_dump('Gls::GetStatus');

		return (string) new GlsStatusTransformer((string) '');
	}
}
