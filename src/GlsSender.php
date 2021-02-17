<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Abstracts\Sender;

class GlsSender extends Sender
{
    public function validate(): bool
    {
        return true;
    }
}