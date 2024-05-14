<?php

namespace TenantCloud\Standard\StaticConstructor;

use RuntimeException;
use Throwable;

class StaticConstructorInvalidUsageException extends RuntimeException
{
	public function __construct(string $message, ?Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}
}
