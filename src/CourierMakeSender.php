<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Gls\Entities\Sender;
use Sylapi\Courier\Contracts\Sender as SenderContract;
use Sylapi\Courier\Contracts\CourierMakeSender as CourierMakeSenderContract;

class CourierMakeSender implements CourierMakeSenderContract
{
    public function makeSender(): SenderContract
    {
        return new Sender();
    }
}
