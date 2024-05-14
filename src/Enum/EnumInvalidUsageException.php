<?php

namespace TenantCloud\Standard\Enum;

use RuntimeException;
use Throwable;

class EnumInvalidUsageException extends RuntimeException
{
	public function __construct(string $message, ?Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}
}
