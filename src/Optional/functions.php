<?php

namespace TenantCloud\Standard\Optional;

/**
 * Create an optional from a value or an empty one.
 *
 * @template T
 *
 * @param T $value
 *
 * @return Optional<T>
 */
function optional(mixed $value): Optional
{
	return new ValueOptional($value);
}

/**
 * Get an empty optional with no value.
 *
 * @return Optional<never>
 */
function empty_optional(): Optional
{
	return EmptyOptional::get();
}
