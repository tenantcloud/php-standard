<?php

namespace TenantCloud\Standard\StaticConstructor;

use Composer\Autoload\ClassLoader;

class StaticConstructorLoaderRegisterer
{
	private static ?StaticConstructorLoader $loader = null;

	public static function loader(): ?StaticConstructorLoader
	{
		return self::$loader;
	}

	public static function register(): void
	{
		if (self::$loader) {
			return;
		}

		$composerClassLoader = array_values(ClassLoader::getRegisteredLoaders())[0];

		self::$loader = new StaticConstructorLoader($composerClassLoader);

		replace_spl_autoloader([$composerClassLoader, 'loadClass'], [self::$loader, 'loadClass']);
	}

	public static function unregister(): void
	{
		if (!self::$loader) {
			return;
		}

		$composerClassLoader = array_values(ClassLoader::getRegisteredLoaders())[0];

		replace_spl_autoloader([self::$loader, 'loadClass'], [$composerClassLoader, 'loadClass'], false);

		self::$loader = null;
	}
}
