<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Contracts\CourierMakeSender;
use Sylapi\Courier\Contracts\Sender;

class GlsCourierMakeSender implements CourierMakeSender
{
    public function makeSender(): Sender
    {
        return new GlsSender();
    }
}
