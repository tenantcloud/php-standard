<?php

namespace Tests\Enum\Stubs;

use TenantCloud\Standard\Enum\Enum;

class NonValueEnumStub extends Enum
{
	public static self $JUST_CASE;

	public static self $INHERITED_CASE;

	private int $value;

	public function __construct(int $value)
	{
		$this->value = $value;
	}

	public function value(): int
	{
		return $this->value;
	}

	protected static function initializeInstances(): void
	{
		self::$JUST_CASE = new self(123);
		self::$INHERITED_CASE = new class (789) extends NonValueEnumStub {
			public function value(): int
			{
				return 456;
			}
		};
	}

	public static function __constructStatic(): void
	{
		parent::__constructStatic();
	}
}
