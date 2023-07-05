<?php

use TenantCloud\Standard\Enum\EnumInvalidUsageException;
use TenantCloud\Standard\Enum\ValueNotFoundException;
use Tests\Enum\Stubs\ValueEnumStub;

ValueEnumStub::__constructStatic();

test('initializes after __constructStatic is called', function () {
	expect($justCase = ValueEnumStub::$ONE_TWO_THREE)->not()->toBeNull();
	expect($inheritedCase = ValueEnumStub::$FOUR_FIVE_SIX)->not()->toBeNull();

	expect($justCase)->not()->toBe($inheritedCase);

	ValueEnumStub::__constructStatic();

	expect(ValueEnumStub::$ONE_TWO_THREE)->toBe($justCase);
	expect(ValueEnumStub::$FOUR_FIVE_SIX)->toBe($inheritedCase);
});

test('public values', function (ValueEnumStub $value, int $expectedValue, string $expectedJson, string $expectedName) {
	expect($value)->not()->toBeNull();
	expect($value->value())->toBe($expectedValue);
	expect(json_encode([$value], JSON_THROW_ON_ERROR))->toBe($expectedJson);
	expect($value->name())->toBe($expectedName);
})->with([
	[ValueEnumStub::$ONE_TWO_THREE, 123, '[123]', 'ONE_TWO_THREE'],
	[ValueEnumStub::$FOUR_FIVE_SIX, 456, '[456]', 'FOUR_FIVE_SIX'],
]);

test('returns an instance from a value', function (int $value, ValueEnumStub $enumValue) {
	expect(ValueEnumStub::fromValue($value))->toBe($enumValue);
	expect(ValueEnumStub::fromValue($enumValue))->toBe($enumValue);
})->with([
	[123, ValueEnumStub::$ONE_TWO_THREE],
	[456, ValueEnumStub::$FOUR_FIVE_SIX],
]);

test('throws when trying to resolve a non-existent value', function (?int $value) {
	ValueEnumStub::fromValue($value);
})->with([
	null,
	789,
])->expectException(ValueNotFoundException::class);

test('returns an array of all instances', function () {
	expect(ValueEnumStub::items())->toHaveCount(2);
	expect(ValueEnumStub::items())->toContain(ValueEnumStub::$ONE_TWO_THREE);
	expect(ValueEnumStub::items())->toContain(ValueEnumStub::$FOUR_FIVE_SIX);
});

test('returns an array of all values', function () {
	expect(ValueEnumStub::values())->toHaveCount(2);
	expect(ValueEnumStub::values())->toContain(123);
	expect(ValueEnumStub::values())->toContain(456);
});

test('throws when trying to serialize', function () {
	serialize(ValueEnumStub::$ONE_TWO_THREE);
})->expectException(EnumInvalidUsageException::class);
