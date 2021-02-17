<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Contracts\CourierPostShipment;

class GlsCourierPostShipment implements CourierPostShipment
{
	private $session;
	
	public function __construct(GlsSession $session)
	{
		$this->session = $session;
	}

	public function postShipment(Booking $booking) : array
	{
		$client = $this->session->client();
		$token = $this->session->token();

		$params = [
			'session' => $token,
			'consigns_ids' => [ $booking->getShipmentId() ],
            'desc' => '' 
		];

		$response = [];
		
		try {
			$result = $client->adePickup_Create($params);
			$response = [
				'shipmentId' => $result->return->id
			];
		} catch (\SoapFault $fault) {
			$response = [
				'error' => $fault->faultstring,
				'code' => $fault->faultcode
			];
		}

		return $response;
	}
}
