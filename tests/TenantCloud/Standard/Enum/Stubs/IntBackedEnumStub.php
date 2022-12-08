<?php

namespace Tests\TenantCloud\Standard\Enum\Stubs;

use TenantCloud\Standard\Enum\BackedEnumExtensions;

enum IntBackedEnumStub: int
{
	/** @use BackedEnumExtensions<int> */
	use BackedEnumExtensions;

	case ONE = 1;
	case TWO = 2;
}
