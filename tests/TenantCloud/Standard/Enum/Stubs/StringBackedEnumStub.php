<?php

namespace Tests\TenantCloud\Standard\Enum\Stubs;

use TenantCloud\Standard\Enum\BackedEnumExtensions;

enum StringBackedEnumStub: string
{
	use BackedEnumExtensions;

	case ONE = 'one';
	case TWO = 'two';
}
