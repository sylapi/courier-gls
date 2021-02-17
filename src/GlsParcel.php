<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Abstracts\Parcel;

class GlsParcel extends Parcel
{
    public function validate(): bool
    {
        return true;
    }
}