<?php

namespace TenantCloud\Standard\Enum;

use RuntimeException;
use Throwable;

class ValueNotFoundException extends RuntimeException
{
	public function __construct(mixed $value, Throwable $previous = null)
	{
		parent::__construct("Value {$value} was not found.", 0, $previous);
	}
}
