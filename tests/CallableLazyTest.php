<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TenantCloud\Standard\Lazy\CallableLazy;
use function TenantCloud\Standard\Lazy\lazy;

test('only resolves once', function () {
	$lazy = new CallableLazy(fn () => Uuid::uuid6());

	expect($lazy->isInitialized())->toBeFalse();
	expect($resolved = $lazy->value())->toBeInstanceOf(UuidInterface::class);
	expect($lazy->isInitialized())->toBeTrue();
	expect($lazy->value())->toBe($resolved);
});

test('function creates a callable lazy', function () {
	$lazy = lazy(fn () => 123);

	expect($lazy)->toBeInstanceOf(CallableLazy::class);
	expect($lazy->value())->toBe(123);
});
