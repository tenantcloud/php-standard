<?php

namespace TenantCloud\Standard\Optional;

/**
 * @implements Optional<never>
 */
final class EmptyOptional implements Optional
{
	public static function get(): self
	{
		static $instance;

		return $instance ??= new self();
	}

	public function value()
	{
		throw new MissingValueException();
	}

	public function hasValue(): bool
	{
		return false;
	}
}
