<?php

namespace Tests\Enum\Stubs;

use TenantCloud\Standard\Enum\BackedEnumExtensions;

enum StringBackedEnumStub: string
{
	/** @use BackedEnumExtensions<string> */
	use BackedEnumExtensions;

	case ONE = 'one';
	case TWO = 'two';
}
