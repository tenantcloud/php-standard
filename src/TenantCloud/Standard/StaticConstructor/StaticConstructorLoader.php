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
	private ClassLoader $delegate;

	public function __construct(ClassLoader $delegate)
	{
		$this->delegate = $delegate;
	}

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
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function getPrefixes()
	{
		return $this->delegate->getPrefixes();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function getPrefixesPsr4()
	{
		return $this->delegate->getPrefixesPsr4();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function getFallbackDirs()
	{
		return $this->delegate->getFallbackDirs();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function getFallbackDirsPsr4()
	{
		return $this->delegate->getFallbackDirsPsr4();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function getClassMap()
	{
		return $this->delegate->getClassMap();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function addClassMap(array $classMap)
	{
		$this->delegate->addClassMap($classMap);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function add($prefix, $paths, $prepend = false)
	{
		$this->delegate->add($prefix, $paths, $prepend);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function addPsr4($prefix, $paths, $prepend = false)
	{
		$this->delegate->addPsr4($prefix, $paths, $prepend);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function set($prefix, $paths)
	{
		$this->delegate->set($prefix, $paths);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function setPsr4($prefix, $paths)
	{
		$this->delegate->setPsr4($prefix, $paths);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function setUseIncludePath($useIncludePath)
	{
		$this->delegate->setUseIncludePath($useIncludePath);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function getUseIncludePath()
	{
		return $this->delegate->getUseIncludePath();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 *
	 * @param mixed $classMapAuthoritative
	 */
	public function setClassMapAuthoritative($classMapAuthoritative)
	{
		$this->delegate->setClassMapAuthoritative($classMapAuthoritative);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function isClassMapAuthoritative()
	{
		return $this->delegate->isClassMapAuthoritative();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function setApcuPrefix($apcuPrefix)
	{
		$this->delegate->setApcuPrefix($apcuPrefix);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function getApcuPrefix()
	{
		return $this->delegate->getApcuPrefix();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function register($prepend = false)
	{
		$this->delegate->register($prepend);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @codeCoverageIgnore
	 */
	public function unregister()
	{
		$this->delegate->unregister();
	}

	/**
	 * {@inheritDoc}
	 *
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
