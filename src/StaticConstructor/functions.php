<?php

namespace TenantCloud\Standard\StaticConstructor;

use Composer\Autoload\ClassLoader;

/**
 * @param array{object, string} $find
 * @param callable(): void      $replace
 */
function replace_spl_autoloader(array $find, callable $replace, bool $throwNotFound = true): void
{
	$loaders = spl_autoload_functions();

	$otherLoaders = [];
	$loaderToReplace = null;

	foreach ($loaders as $loader) {
		if (is_array($loader) && $loader[0] === $find[0] && $loader[1] === $find[1]) {
			$loaderToReplace = $loader;

			break;
		}

		$otherLoaders[] = $loader;
	}

	if (!$loaderToReplace) {
		if (!$throwNotFound) {
			return;
		}

		throw new StaticConstructorInvalidUsageException(get_class($find[0]) . ' was not found in registered autoloaders');
	}

	// Unregister Composer autoloader and all preceding autoloaders as __autoload() implementation
	foreach ([...$otherLoaders, $loaderToReplace] as $loader) {
		if (is_array($loader) && $loader[0] instanceof ClassLoader) {
			$loader[0]->unregister();

			continue;
		}

		spl_autoload_unregister($loader);
	}

	$replace();

	// Register given function from $loadersToRestore as __autoload implementation
	foreach (array_reverse($otherLoaders) as $loader) {
		spl_autoload_register($loader, true, true);
	}
}
