<?php

use TenantCloud\Standard\Enum\EnumInvalidUsageException;
use Tests\Enum\Stubs\NonValueEnumStub;

NonValueEnumStub::__constructStatic();

test('initializes after __constructStatic is called', function () {
	expect($justCase = NonValueEnumStub::$JUST_CASE)->not()->toBeNull();
	expect($inheritedCase = NonValueEnumStub::$INHERITED_CASE)->not()->toBeNull();

	expect($justCase)->not()->toBe($inheritedCase);

	NonValueEnumStub::__constructStatic();

	expect(NonValueEnumStub::$JUST_CASE)->toBe($justCase);
	expect(NonValueEnumStub::$INHERITED_CASE)->toBe($inheritedCase);
});

test('public values', function (NonValueEnumStub $value, int $expectedValue, string $expectedName) {
	expect($value)->not()->toBeNull();
	expect($value->value())->toBe($expectedValue);
	expect($value->name())->toBe($expectedName);
})->with([
	[NonValueEnumStub::$JUST_CASE, 123, 'JUST_CASE'],
	[NonValueEnumStub::$INHERITED_CASE, 456, 'INHERITED_CASE'],
]);

test('returns an array of all instances', function () {
	expect(NonValueEnumStub::items())->toHaveCount(2);
	expect(NonValueEnumStub::items())->toContain(NonValueEnumStub::$JUST_CASE);
	expect(NonValueEnumStub::items())->toContain(NonValueEnumStub::$INHERITED_CASE);
});

test('throws when attempting to serialize', function () {
	serialize(NonValueEnumStub::$JUST_CASE);
})->expectException(EnumInvalidUsageException::class);
