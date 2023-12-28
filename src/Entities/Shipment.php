<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls\Entities;

use Rakit\Validation\Validator;
use Sylapi\Courier\Abstracts\Shipment as ShipmentAbstract;
use Sylapi\Courier\Contracts\Shipment as ShipmentContract;

class Shipment extends ShipmentAbstract
{
    private $notes;

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): ShipmentContract
    {
        $this->notes = $notes;

        return $this;
    }

    public function getQuantity(): int
    {
        return 1;
    }

    public function validate(): bool
    {
        $rules = [
            'quantity' => 'required|min:1|max:1',
            'parcel'   => 'required',
            'sender'   => 'required',
            'receiver' => 'required',
        ];

        $data = [
            'quantity' => $this->getQuantity(),
            'parcel'   => $this->getParcel(),
            'sender'   => $this->getSender(),
            'receiver' => $this->getReceiver(),
        ];

        $validator = new Validator();

        $validation = $validator->validate($data, $rules);
        if ($validation->fails()) {
            $this->setErrors($validation->errors()->toArray());

            return false;
        }

        return true;
    }
}
