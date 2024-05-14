<?php

namespace Sylapi\Courier\Gls\Services;

use InvalidArgumentException;
use Sylapi\Courier\Abstracts\Service;

class Sat extends Service
{
    public function handle(): array
    {
        $consign = $this->getRequest();
        
        if($consign === null) {
            throw new InvalidArgumentException('Request is not defined');
        }
        
        $consign['srv_bool']['s12'] = true;
        
        return $consign;
    }
}
