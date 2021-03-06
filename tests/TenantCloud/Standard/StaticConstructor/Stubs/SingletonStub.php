<?php

namespace Tests\TenantCloud\Standard\StaticConstructor\Stubs;

use TenantCloud\Standard\StaticConstructor\HasStaticConstructor;

class SingletonStub implements HasStaticConstructor
{
	public static self $instance;

	public static function __constructStatic(): void
	{
		self::$instance = new self();
	}
}
