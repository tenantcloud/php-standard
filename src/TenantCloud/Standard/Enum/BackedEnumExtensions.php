<?php

namespace TenantCloud\Standard\Enum;

use BackedEnum;

/**
 * @mixin BackedEnum
 */
trait BackedEnumExtensions
{
	/**
	 * @return ($this is IntBackedEnum ? int[] : string[])
	 */
	public static function values(): array
	{
		return array_column(self::cases(), 'value');
	}
}
