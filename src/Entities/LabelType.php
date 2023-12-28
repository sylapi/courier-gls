<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls\Entities;

use Sylapi\Courier\Abstracts\LabelType as LabelTypeAbstract;

class LabelType extends LabelTypeAbstract
{
    const DEFAULT_LABEL_TYPE = 'one_label_on_a4_lt_pdf';

    private string $labelType;

    public function setLabelType(string $labelType): self
    {
        $this->labelType = $labelType;

        return $this;
    }

    public function getLabelType(): string
    {
        return  $this->labelType ?? self::DEFAULT_LABEL_TYPE;
    }

    public function validate(): bool
    {
        return true;
    }
}
