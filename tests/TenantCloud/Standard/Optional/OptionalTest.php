<?php

namespace Tests\TenantCloud\Standard\Optional;

use stdClass;
use function TenantCloud\Standard\Optional\empty_optional;
use TenantCloud\Standard\Optional\MissingValueException;
use function TenantCloud\Standard\Optional\optional;

test('value optional', function ($value) {
	$optional = optional($value);

	expect($optional->value())->toBe($value);
	expect($optional->hasValue())->toBeTrue();
})->with([
	null,
	false,
	true,
	mt_rand(),
	new stdClass(),
]);

test('empty optional', function () {
	$optional = empty_optional();

	expect($optional->hasValue())->toBeFalse();
	expect(fn () => $optional->value())->toThrow(MissingValueException::class);
	expect($optional)->toBe(empty_optional());
});
