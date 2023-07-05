<?php

namespace TenantCloud\Standard\Optional;

/**
 * @template-covariant T
 *
 * @implements Optional<T>
 */
final class ValueOptional implements Optional
{
	/**
	 * @param T $value
	 */
	public function __construct(
		private mixed $value,
	) {}

	public function value()
	{
		return $this->value;
	}

	public function hasValue(): bool
	{
		return true;
	}
}
