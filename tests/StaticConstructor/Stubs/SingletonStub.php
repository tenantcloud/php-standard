<?php

namespace Tests\StaticConstructor\Stubs;

use TenantCloud\Standard\StaticConstructor\HasStaticConstructor;

class SingletonStub implements HasStaticConstructor
{
	public static ?self $instance = null;

	public static function __constructStatic(): void
	{
		self::$instance = new self();
	}
}
