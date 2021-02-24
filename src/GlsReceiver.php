<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Abstracts\Receiver;
use Rakit\Validation\Validator;

class GlsReceiver extends Receiver
{
    public function validate(): bool
    {
        $rules = [
            'firstName' => 'required',
            'surname' => 'required',
            'countryCode' => 'required|min:2|max:2',
            'city' => 'required',
            'zipCode' => 'required',
            'street' => 'required',
            'email' => 'nullable|email',
        ];
        $data = $this->toArray();

        $validator = new Validator;

        $validation = $validator->validate($data, $rules);
        if ($validation->fails()) {
            $this->setErrors($validation->errors()->toArray());
            return false;
        }
        return true;
    }
}
