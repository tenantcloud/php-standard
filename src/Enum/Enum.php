<?php

namespace TenantCloud\Standard\Enum;

use ReflectionClass;
use TenantCloud\Standard\StaticConstructor\HasStaticConstructor;

/**
 * Each static property should be declared as read-only, unfortunately, PHP does not support this (see RFC https://wiki.php.net/rfc/readonly_properties)
 */
abstract class Enum implements HasStaticConstructor
{
	/** @var array<class-string<static>, true> */
	protected static array $initializedEnums = [];

	/**
	 * @return array<string, static>
	 */
	final public static function items(): array
	{
		return array_filter(
			(new ReflectionClass(static::class))->getStaticProperties(),
			static fn ($value) => $value instanceof static
		);
	}

	/**
	 * Variable (case) name of the enum.
	 */
	final public function name(): string
	{
		$name = array_search($this, static::items(), true);

		if ($name === false) {
			throw new EnumInvalidUsageException('Can not find $this in ' . static::class . '::items(). It seems that the static property was overwritten. This is not allowed.');
		}

		return $name;
	}

	/**
	 * Override this method to manually initialize Enum values. Useful when __construct() accepts at least one argument.
	 * Enum objects does not have any properties by default.
	 */
	abstract protected static function initializeInstances(): void;

	protected static function initialize(): void
	{
		if (isset(self::$initializedEnums[static::class])) {
			return;
		}

		self::$initializedEnums[static::class] = true;

		static::initializeInstances();
	}

	/**
	 * Override this method to custom "programmer-friendly" string representation of Enum value.
	 * It returns Enum value by default.
	 */
	public function __toString(): string
	{
		return $this->name();
	}

	public static function __constructStatic(): void
	{
		if (static::class === self::class) {
			return;
		}

		static::initialize();
	}

	public function __debugInfo(): ?array
	{
		return [
			'name' => $this->name(),
		];
	}

	/**
	 * Enum instances are singletons and hence should not be cloned.
	 *
	 * @return never
	 */
	final public function __clone()
	{
		throw new EnumInvalidUsageException('Enum instances cannot be cloned as they are singletons by design.');
	}

	/**
	 * {@see Enum::unserialize()} for explanation.
	 *
	 * @return never
	 */
	final public function __serialize(): array
	{
		throw new EnumInvalidUsageException('Enum instances cannot be serialized because it cannot be deserialized.');
	}

	/**
	 * Normal serialization is disabled due to being unable to return a specific instance on deserialization -
	 * instead PHP creates the instance for us and calls unserialize() on that instance.
	 *
	 * https://github.com/dbalabka/php-enumeration#serialization
	 *
	 * @return never
	 */
	final public function __unserialize(array $data): void
	{
		throw new EnumInvalidUsageException('Enum instances cannot be deserialized through built-in serialization due to it\'s limitations.');
	}
}
