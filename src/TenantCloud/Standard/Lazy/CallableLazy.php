<?php

namespace TenantCloud\Standard\Lazy;

use Closure;

/**
 * Simple implementation of {@see Lazy} based on a callback.
 *
 * @template T
 * @implements Lazy<T>
 */
class CallableLazy implements Lazy
{
	/** @var T */
	private $value;

	private bool $initialized = false;

	/** @var Closure(): T */
	private Closure $resolveValue;

	public function __construct(Closure $resolve)
	{
		$this->resolveValue = $resolve;
	}

	/**
	 * {@inheritdoc}
	 */
	public function value()
	{
		$this->initialize();

		return $this->value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isInitialized(): bool
	{
		return $this->initialized;
	}

	/**
	 * Initialize the lazy by resolving the value. Will not do anything if called twice.
	 */
	private function initialize(): void
	{
		if ($this->isInitialized()) {
			return;
		}

		$this->value = ($this->resolveValue)();
		$this->initialized = true;
	}
}
