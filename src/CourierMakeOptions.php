<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Gls\Entities\Options;
use Sylapi\Courier\Contracts\Options as OptionsContract;
use Sylapi\Courier\Contracts\CourierMakeOptions as CourierMakeOptionsContract;

class CourierMakeOptions implements CourierMakeOptionsContract
{
    public function makeOptions(): OptionsContract
    {
        return new Options();
    }
}
