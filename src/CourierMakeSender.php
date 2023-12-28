<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierMakeSender as CourierMakeSenderContract;
use Sylapi\Courier\Contracts\Sender as SenderContract;
use Sylapi\Courier\Contracts\Sender;

class CourierMakeSender implements CourierMakeSenderContract
{
    public function makeSender(): SenderContract
    {
        return new Sender();
    }
}
