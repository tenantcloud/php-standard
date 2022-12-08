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
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function value()
	{
		return $this->value;
	}

	/**
	 * @inheritDoc
	 */
	public function hasValue(): bool
	{
		return true;
	}
}
