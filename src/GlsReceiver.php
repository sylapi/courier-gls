<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Abstracts\Receiver;

class GlsReceiver extends Receiver
{
    public function validate(): bool
    {
        return true;
    }
}
