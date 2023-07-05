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
 *
 * @implements Iterator<I>
 */
class PageIterator implements Iterator
{
	/** @var Lazy<Generator<int, I, mixed, void>> */
	private Lazy $delegate;

	/**
	 * @param callable(int): list<I> $nextItems
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

	public function current(): mixed
	{
		return $this->delegate->value()->current();
	}

	public function next(): void
	{
		$this->delegate->value()->next();
	}

	public function key(): mixed
	{
		return $this->delegate->value()->key();
	}

	public function valid(): bool
	{
		return $this->delegate->value()->valid();
	}

	public function rewind(): void
	{
		$this->delegate->value()->rewind();
	}
}
