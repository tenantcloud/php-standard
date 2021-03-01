<?php

namespace Tests\TenantCloud\Standard\StaticConstructor;

trait StaticConstructorLoaderState
{
	private array $oldAutoloadFunctions;

	protected function saveAutoLoaders(): void
	{
		$this->oldAutoloadFunctions = spl_autoload_functions();
	}

	protected function restoreAutoLoaders(): void
	{
		$this->clearAutoLoaders();

		foreach ($this->oldAutoloadFunctions as $autoloadFunction) {
			spl_autoload_register($autoloadFunction);
		}
	}

	protected function clearAutoLoaders(): void
	{
		foreach (spl_autoload_functions() as $autoloadFunction) {
			spl_autoload_unregister($autoloadFunction);
		}
	}
}
