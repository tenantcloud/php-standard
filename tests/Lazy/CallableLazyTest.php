<?php

use stdClass;
use TenantCloud\Standard\Lazy\CallableLazy;

use function TenantCloud\Standard\Lazy\lazy;

test('only resolves once', function ($value) {
	$hasCallbackExecuted = false;
	$lazy = new CallableLazy(function () use ($value, &$hasCallbackExecuted) {
		expect($hasCallbackExecuted)->toBeFalse();

		$hasCallbackExecuted = true;

		return $value;
	});

	expect($lazy->isInitialized())->toBeFalse();
	expect($resolved = $lazy->value())->toBe($value);
	expect($lazy->isInitialized())->toBeTrue();
	expect($lazy->value())->toBe($resolved);
})->with([
	null,
	false,
	true,
	mt_rand(),
	new stdClass(),
]);

test('function creates a callable lazy', function () {
	$lazy = lazy(fn () => 123);

	expect($lazy)->toBeInstanceOf(CallableLazy::class);
	expect($lazy->value())->toBe(123);
});
