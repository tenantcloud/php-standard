<?php

namespace TenantCloud\Standard\StaticConstructor;

use Composer\Autoload\ClassLoader;

/**
 * Unfortunately, PHP does not support static initialization, so we're emulating it with a custom class loader.
 *
 * See static init RFC: https://wiki.php.net/rfc/static_class_constructor
 *
 * @see https://github.com/dbalabka/php-enumeration/blob/master/src/StaticConstructorLoader/StaticConstructorLoader.php
 */
final class StaticConstructorLoader
{
	private ClassLoader $classLoader;

	public function __construct(ClassLoader $classLoader)
	{
		$this->classLoader = $classLoader;

		// Find Composer autoloader
		$loaders = spl_autoload_functions();

		if ($loaders === false) {
			throw new StaticConstructorInvalidUsageException('Autoload stack is not activated.');
		}

		$otherLoaders = [];
		$composerLoader = null;

		foreach ($loaders as $loader) {
			if (is_array($loader)) {
				if ($loader[0] === $classLoader) {
					$composerLoader = $loader;

					break;
				}

				if ($loader[0] instanceof self) {
					throw new StaticConstructorInvalidUsageException(self::class . ' already registered');
				}
			}
			$otherLoaders[] = $loader;
		}

		if (!$composerLoader) {
			throw new StaticConstructorInvalidUsageException(ClassLoader::class . ' was not found in registered autoloaders');
		}

		// Unregister Composer autoloader and all preceding autoloaders as __autoload() implementation
		foreach ([...$otherLoaders, $composerLoader] as $loader) {
			spl_autoload_unregister($loader);
		}

		// Restoring the original queue order
		$loadersToRestore = [[$this, 'loadClass'], ...array_reverse($otherLoaders)];

		// Register given function from $loadersToRestore as __autoload implementation
		foreach ($loadersToRestore as $loader) {
			spl_autoload_register($loader, true, true);
		}
	}

	/**
	 * @param class-string $className
	 */
	public function loadClass(string $className): ?bool
	{
		$result = $this->classLoader->loadClass($className);

		if ($result && $this->shouldCallConstructor($className)) {
			/* @var class-string<HasStaticConstructor> $className */
			$className::__constructStatic();
		}

		return $result;
	}

	private function shouldCallConstructor(string $className): bool
	{
		return $className !== HasStaticConstructor::class &&
			is_a($className, HasStaticConstructor::class, true);
	}
}
