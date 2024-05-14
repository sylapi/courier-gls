<?php

namespace Sylapi\Courier\Gls\Services;

use InvalidArgumentException;

use Sylapi\Courier\Abstracts\Services\COD as CODAbstract;

class COD extends CODAbstract
{
    public function handle(): array
    {
        $consign = $this->getRequest();
        
        if($consign === null) {
            throw new InvalidArgumentException('Request is not defined');
        }

        if(!$this->getAmount()) {
            throw new InvalidArgumentException('Amount is not defined');
        }

        $consign['srv_bool']['cod'] = true;
        $consign['srv_bool']['cod_amount'] = $this->getAmount();

        return $consign;
    }
}
