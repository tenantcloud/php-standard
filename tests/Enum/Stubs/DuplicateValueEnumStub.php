<?php

namespace Tests\Enum\Stubs;

use TenantCloud\Standard\Enum\ValueEnum;

final class DuplicateValueEnumStub extends ValueEnum
{
	public static self $ONE_TWO_THREE;

	public static self $ONE_TWO_THREE_DUPLICATE;

	protected static function initializeInstances(): void
	{
		self::$ONE_TWO_THREE = new self(123);
		self::$ONE_TWO_THREE_DUPLICATE = new self(123);
	}
}
