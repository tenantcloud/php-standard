<?php

namespace TenantCloud\Standard\StaticConstructor;

use Composer\Autoload\ClassLoader;

class StaticConstructorLoaderRegisterer
{
	private static ?StaticConstructorLoader $loader = null;

	private static ?ClassLoader $composerClassLoader = null;

	public static function loader(): ?StaticConstructorLoader
	{
		return self::$loader;
	}

	public static function register(): void
	{
		if (self::$loader) {
			return;
		}

		self::$composerClassLoader = self::findComposerLoader();
		self::$loader = new StaticConstructorLoader(self::$composerClassLoader);

		replace_spl_autoloader(
			[self::$composerClassLoader, 'loadClass'],
			function () {
				assert(self::$loader !== null);

				self::$loader->register(true);
			},
		);
	}

	public static function unregister(): void
	{
		if (!self::$loader) {
			return;
		}

		assert(self::$composerClassLoader !== null);

		replace_spl_autoloader(
			[self::$loader, 'loadClass'],
			function () {
				assert(self::$composerClassLoader !== null);

				self::$composerClassLoader->register(true);
			},
			false
		);

		self::$loader = null;
	}

	/**
	 * @internal
	 */
	public static function findComposerLoader(): ClassLoader
	{
		$loaders = spl_autoload_functions();

		foreach ($loaders as $loader) {
			if (is_array($loader) && $loader[0] instanceof ClassLoader) {
				return $loader[0];
			}
		}

		throw new StaticConstructorInvalidUsageException(ClassLoader::class . ' was not found in registered autoloaders');
	}
}
