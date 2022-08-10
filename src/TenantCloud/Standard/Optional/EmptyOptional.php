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

	/**
	 * {@inheritdoc}
	 */
	public function value()
	{
		throw new MissingValueException();
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasValue(): bool
	{
		return false;
	}
}
