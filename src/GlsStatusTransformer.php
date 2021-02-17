<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Abstracts\StatusTransformer;
use Sylapi\Courier\Enums\StatusType;

class GlsStatusTransformer extends StatusTransformer
{
	public $statuses = [
		'PROCESSING' => StatusType::PROCESSING
	];
}
