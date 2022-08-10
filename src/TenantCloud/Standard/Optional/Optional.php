<?php

namespace TenantCloud\Standard\Optional;

/**
 * Represents a possibly missing value that cannot or should not be described as null.
 *
 * @template-covariant T
 */
interface Optional
{
	/**
	 * Gets the value of the optional if one's present, otherwise throws {@see MissingValueException}.
	 *
	 * @return T
	 */
	public function value();

	/**
	 * Whether it has a value or not.
	 */
	public function hasValue(): bool;
}
