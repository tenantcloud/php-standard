<?php

namespace Tests\TenantCloud\Standard\Enum;

use Tests\TenantCloud\Standard\Enum\Stubs\IntBackedEnumStub;
use Tests\TenantCloud\Standard\Enum\Stubs\StringBackedEnumStub;

test('returns all values', function (string $class, array $expected) {
	expect($class::values())->toBe($expected);
})->with([
	[StringBackedEnumStub::class, ['one', 'two']],
	[IntBackedEnumStub::class, [1, 2]],
]);
