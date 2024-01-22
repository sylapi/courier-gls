<?php

namespace Sylapi\Courier\Gls\Responses;
use Sylapi\Courier\Gls\Entities\Booking;
use Sylapi\Courier\Responses\Shipment as ShipmentResponse;
use Sylapi\Courier\Contracts\Response as ResponseContract;

class Shipment extends ShipmentResponse
{
    private $referenceId;

    public function setReferenceId(string $referenceId): ResponseContract
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }

    public function getBooking() : ?Booking
    {

        if(!$this->getResponse()) {
            return null;
        }

        $booking = new Booking();
        $booking->setShipmentId($this->getShipmentId());

        return $booking;

    }
}
