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
final class StaticConstructorLoader extends ClassLoader
{
	public function __construct(private ClassLoader $delegate) {}

	/**
	 * @param class-string $className
	 */
	public function loadClass($className): ?bool
	{
		$loaded = $this->delegate->loadClass($className);

		if ($loaded && $this->shouldCallConstructor($className)) {
			/* @var class-string<HasStaticConstructor> $className */
			$className::__constructStatic();
		}

		return $loaded;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrefixes()
	{
		return $this->delegate->getPrefixes();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrefixesPsr4()
	{
		return $this->delegate->getPrefixesPsr4();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFallbackDirs()
	{
		return $this->delegate->getFallbackDirs();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFallbackDirsPsr4()
	{
		return $this->delegate->getFallbackDirsPsr4();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getClassMap()
	{
		return $this->delegate->getClassMap();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function addClassMap(array $classMap)
	{
		$this->delegate->addClassMap($classMap);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function add($prefix, $paths, $prepend = false)
	{
		$this->delegate->add($prefix, $paths, $prepend);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function addPsr4($prefix, $paths, $prepend = false)
	{
		$this->delegate->addPsr4($prefix, $paths, $prepend);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function set($prefix, $paths)
	{
		$this->delegate->set($prefix, $paths);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setPsr4($prefix, $paths)
	{
		$this->delegate->setPsr4($prefix, $paths);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setUseIncludePath($useIncludePath)
	{
		$this->delegate->setUseIncludePath($useIncludePath);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUseIncludePath()
	{
		return $this->delegate->getUseIncludePath();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setClassMapAuthoritative($classMapAuthoritative)
	{
		$this->delegate->setClassMapAuthoritative($classMapAuthoritative);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function isClassMapAuthoritative()
	{
		return $this->delegate->isClassMapAuthoritative();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setApcuPrefix($apcuPrefix)
	{
		$this->delegate->setApcuPrefix($apcuPrefix);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getApcuPrefix()
	{
		return $this->delegate->getApcuPrefix();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function register($prepend = false)
	{
		spl_autoload_register([$this, 'loadClass'], true, $prepend);

		$that = $this;

		(function () use ($that, $prepend) {
			$vendorDir = $this->vendorDir;

			if ($vendorDir === null) {
				return;
			}

			if ($prepend) {
				self::$registeredLoaders = [$vendorDir => $that] + self::$registeredLoaders;
			} else {
				unset(self::$registeredLoaders[$vendorDir]);
				self::$registeredLoaders[$vendorDir] = $that;
			}
		})->call($this->delegate);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function unregister()
	{
		spl_autoload_unregister([$this, 'loadClass']);

		(function () {
			$vendorDir = $this->vendorDir;

			if ($vendorDir !== null) {
				unset(self::$registeredLoaders[$vendorDir]);
			}
		})->call($this->delegate);
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function findFile($class)
	{
		return $this->delegate->findFile($class);
	}

	private function shouldCallConstructor(string $className): bool
	{
		return $className !== HasStaticConstructor::class &&
			is_a($className, HasStaticConstructor::class, true);
	}
}
