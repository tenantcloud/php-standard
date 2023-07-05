<?php

use TenantCloud\Standard\Enum\EnumInvalidUsageException;
use Tests\Enum\Stubs\DuplicateValueEnumStub;

test('duplicated values enum initialization results in an error', function () {
	DuplicateValueEnumStub::__constructStatic();
})->expectException(EnumInvalidUsageException::class);
