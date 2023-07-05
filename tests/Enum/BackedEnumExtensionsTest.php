<?php

use Tests\Enum\Stubs\IntBackedEnumStub;
use Tests\Enum\Stubs\StringBackedEnumStub;

test('returns all values', function (string $class, array $expected) {
	/** @var class-string<StringBackedEnumStub|IntBackedEnumStub> $class */
	expect($class::values())->toBe($expected);
})->with([
	[StringBackedEnumStub::class, ['one', 'two']],
	[IntBackedEnumStub::class, [1, 2]],
]);
