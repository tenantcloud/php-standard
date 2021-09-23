<?php

namespace TenantCloud\Standard\Iterators;

use Generator;
use Iterator;
use TenantCloud\Standard\Lazy\Lazy;
use function TenantCloud\Standard\Lazy\lazy;

/**
 * Iterates over pages of items till the last page. Last page is detected when there are zero items on the page.
 *
 * @template-covariant I
 * @implements Iterator<I>
 */
class PageIterator implements Iterator
{
	/** @var Lazy<Generator<I>> */
	private Lazy $delegate;

	/**
	 * @param callable(int): Array<I> $nextItems
	 */
	public function __construct(callable $nextItems)
	{
		$this->delegate = lazy(function () use ($nextItems): Generator {
			$page = 1;

			do {
				$items = $nextItems($page);

				yield from $items;

				$page++;
			} while (!empty($items));
		});
	}

	/**
	 * {@inheritdoc}
	 */
	public function current()
	{
		return $this->delegate->value()->current();
	}

	/**
	 * {@inheritdoc}
	 */
	public function next()
	{
		return $this->delegate->value()->next();
	}

	/**
	 * {@inheritdoc}
	 */
	public function key()
	{
		return $this->delegate->value()->key();
	}

	/**
	 * {@inheritdoc}
	 */
	public function valid(): bool
	{
		return $this->delegate->value()->valid();
	}

	/**
	 * {@inheritdoc}
	 */
	public function rewind(): void
	{
		$this->delegate->value()->rewind();
	}
}
