<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\Receiver as ReceiverContract;
use Sylapi\Courier\Contracts\CourierMakeReceiver as CourierMakeReceiverContract;
use Sylapi\Courier\Contracts\Receiver;

class CourierMakeReceiver implements CourierMakeReceiverContract
{
    public function makeReceiver(): ReceiverContract
    {
        return new Receiver();
    }
}
