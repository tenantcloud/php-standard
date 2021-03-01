<?php

namespace TenantCloud\Standard\Lazy;

use Closure;

/**
 * Create a callable lazy.
 *
 * @template T
 *
 * @param Closure(): T $resolve
 *
 * @return Lazy<T>
 */
function lazy(Closure $resolve): Lazy
{
	return new CallableLazy($resolve);
}
