<?php

namespace TenantCloud\Standard\Enum;

use RuntimeException;
use Throwable;

class ValueNotFoundException extends RuntimeException
{
	/**
	 * @param mixed $value
	 */
	public function __construct($value, Throwable $previous = null)
	{
		parent::__construct("Value {$value} was not found.", 0, $previous);
	}
}
