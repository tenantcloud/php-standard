<?php

namespace TenantCloud\Standard\Iterators;

use Iterator;

/**
 * Similar to {@see \ArrayIterator}, except it's specifically made for single-dimensional (value) arrays.
 * This means we can now operate with indices and insert (merge) items in between without ruining the interface.
 *
 * @template T
 * @implements Iterator<T>
 */
class ArrayValuesIterator implements Iterator
{
	private int $position;

	/** @var T[] */
	private array $items;

	/**
	 * @param T[] $items
	 */
	public function __construct(array $items = [])
	{
		$this->items = $items;

		$this->rewind();
	}

	/**
	 * Merges new elements at given position.
	 *
	 * @param T[] $values
	 */
	public function merge(array $values, int $at): void
	{
		array_splice($this->items, $at, 0, $values);
	}

	/**
	 * {@inheritdoc}
	 */
	public function current(): mixed
	{
		return $this->items[$this->position];
	}

	/**
	 * {@inheritdoc}
	 */
	public function next(): void
	{
		$this->position++;
	}

	/**
	 * {@inheritdoc}
	 */
	public function key(): mixed
	{
		return $this->position;
	}

	/**
	 * {@inheritdoc}
	 */
	public function valid(): bool
	{
		return isset($this->items[$this->position]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rewind(): void
	{
		$this->position = 0;
	}
}
