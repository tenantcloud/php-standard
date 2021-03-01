<?php

namespace TenantCloud\Standard\StaticConstructor;

/**
 * @param array{object, string} $find
 * @param array{object, string} $replace
 */
function replace_spl_autoloader(array $find, array $replace, bool $throwNotFound = true): void
{
	$loaders = spl_autoload_functions();

	if ($loaders === false) {
		throw new StaticConstructorInvalidUsageException('Autoload stack is not activated.');
	}

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
		spl_autoload_unregister($loader);
	}

	// Restoring the original queue order
	$loadersToRestore = [$replace, ...array_reverse($otherLoaders)];

	// Register given function from $loadersToRestore as __autoload implementation
	foreach ($loadersToRestore as $loader) {
		spl_autoload_register($loader, true, true);
	}
}
