<?php

namespace TenantCloud\Standard\Enum;

use Illuminate\Support\Arr;
use JsonSerializable;

/**
 * An enum that has a value and respective methods.
 *
 * @template T
 */
abstract class ValueEnum extends Enum implements JsonSerializable
{
	/** @var T */
	protected $value;

	/**
	 * @param T $value
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function __toString(): string
	{
		return (string) $this->value();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function __constructStatic(): void
	{
		if (static::class === self::class) {
			return;
		}

		parent::__constructStatic();
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return T[]
	 */
	public static function values(): array
	{
		return array_values(
			array_map(
				static fn (self $object) => $object->value(),
				static::items()
			)
		);
	}

	/**
	 * Getting constant instance from constant value
	 *
	 * @param T|static|null $value
	 *
	 * @return static
	 */
	public static function fromValue($value): self
	{
		// If given an instance of this Enum, just return it.
		if ($value instanceof static) {
			return $value;
		}

		$object = Arr::first(static::items(), static fn (self $object) => $value === $object->value());

		if (!$object) {
			throw new ValueNotFoundException($value);
		}

		return $object;
	}

	/**
	 * {@inheritDoc}
	 */
	final protected static function initialize(): void
	{
		parent::initialize();

		$values = static::values();

		if (count($values) !== count(array_unique($values))) {
			throw new EnumInvalidUsageException('Class constants from ' . static::class . ' had duplicated values.');
		}
	}

	/**
	 * Value that the enum was initialized with.
	 *
	 * @return T
	 */
	final public function value()
	{
		return $this->value;
	}

	/**
	 * {@inheritdoc}
	 *
	 * While normal serialization is disabled due to being unable to deserialize the enum back,
	 * JSON serialization can still be used for better developer experience as there's no
	 * "jsonUnserialize" method and hence no sort of problems {@see Enum::serialize()} has.
	 */
	public function jsonSerialize(): string
	{
		return json_encode($this->value(), JSON_THROW_ON_ERROR);
	}
}
