<?php

namespace TenantCloud\Standard\StaticConstructor;

use Composer\Autoload\ClassLoader;
use Exception;

/**
 * Unfortunately, PHP does not support static initialization, so we're emulating it with a custom class loader.
 *
 * See static init RFC: https://wiki.php.net/rfc/static_class_constructor
 *
 * @see https://github.com/dbalabka/php-enumeration/blob/master/src/StaticConstructorLoader/StaticConstructorLoader.php
 */
final class StaticConstructorLoader
{
	/** @var ClassLoader */
	private $classLoader;

	public function __construct(ClassLoader $classLoader)
	{
		$this->classLoader = $classLoader;

		// Find Composer autoloader
		$loaders = spl_autoload_functions();

		$otherLoaders = [];
		$composerLoader = null;

		foreach ($loaders as $loader) {
			if (is_array($loader)) {
				if ($loader[0] === $classLoader) {
					$composerLoader = $loader;

					break;
				}

				if ($loader[0] instanceof self) {
					throw new Exception(sprintf('%s already registered', self::class));
				}
			}
			$otherLoaders[] = $loader;
		}

		if (!$composerLoader) {
			throw new Exception(sprintf('%s was not found in registered autoloaders', ClassLoader::class));
		}

		// Unregister Composer autoloader and all preceding autoloaders as __autoload() implementation
		array_map('spl_autoload_unregister', array_merge($otherLoaders, [$composerLoader]));

		// Restoring the original queue order
		$loadersToRestore = array_merge([$this->getLoadClassCallback()], array_reverse($otherLoaders));

		// Filling array with true values
		$flagTrue = array_fill(0, count($loadersToRestore), true);

		// Register given function from $loadersToRestore as __autoload implementation
		array_map('spl_autoload_register', $loadersToRestore, $flagTrue, $flagTrue);
	}

	private function getLoadClassCallback(): callable
	{
		/*
		 * @param class-string<HasStaticConstructor> $className
		 */
		return function (string $className) {
			$result = $this->classLoader->loadClass($className);

			if ($this->shouldCallConstructor($className, $result)) {
				$className::__constructStatic();
			}

			return $result;
		};
	}

	private function shouldCallConstructor(string $className, ?bool $isLoaded = null): bool
	{
		if ($isLoaded !== true) {
			return false;
		}

		// Excluding our interface
		if ($className === HasStaticConstructor::class) {
			return false;
		}

		return is_a($className, HasStaticConstructor::class, true);
	}
}
