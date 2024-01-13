<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls\Entities;

use Sylapi\Courier\Abstracts\Options as OptionsAbstract;

class Options extends OptionsAbstract
{
    public function getPostDate(): string
    {
        return $this->get('postDate', date('Y-m-d'));
    }

    public function setPostDate(string $postDate): self
    {
        $this->set('postDate', $postDate);
        return $this;
    }

    public function validate(): bool
    {
        return true;
    }
}
