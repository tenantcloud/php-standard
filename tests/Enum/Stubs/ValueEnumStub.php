<?php

namespace Tests\Enum\Stubs;

use TenantCloud\Standard\Enum\ValueEnum;

/**
 * @extends ValueEnum<int>
 */
class ValueEnumStub extends ValueEnum
{
	public static self $ONE_TWO_THREE;

	public static self $FOUR_FIVE_SIX;

	protected static function initializeInstances(): void
	{
		self::$ONE_TWO_THREE = new self(123);
		self::$FOUR_FIVE_SIX = new self(456);
	}
}
