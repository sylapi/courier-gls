<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Abstracts\Sender;
use Rakit\Validation\Validator;

class GlsSender extends Sender
{
    public function validate(): bool
    {
        $rules = [
            'fullName' => 'required',
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
