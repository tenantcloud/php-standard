<?php

namespace Tests\TenantCloud\Standard\Enum;

use TenantCloud\Standard\Enum\EnumInvalidUsageException;
use Tests\TenantCloud\Standard\Enum\Stubs\DuplicateValueEnumStub;

test('duplicated values enum initialization results in an error', function () {
	DuplicateValueEnumStub::__constructStatic();
})->expectException(EnumInvalidUsageException::class);
