<?php

namespace TenantCloud\Standard\Enum;

use BackedEnum;

/**
 * @template TEnumValue
 *
 * @mixin BackedEnum
 */
trait BackedEnumExtensions
{
	/**
	 * @return TEnumValue[]
	 */
	public static function values(): array
	{
		return array_column(self::cases(), 'value');
	}
}
