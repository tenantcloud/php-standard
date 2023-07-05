<?php

namespace TenantCloud\Standard\StaticConstructor;

/**
 * Denotes a class that has a static constructor.
 */
interface HasStaticConstructor
{
	/**
	 * Magic method called exactly once when a class is loaded through auto-loading.
	 *
	 * This is technically auto-loading non-public method (as is a normal __construct), so it's prefixed with __ to not be called accidentally.
	 */
	public static function __constructStatic(): void;
}
