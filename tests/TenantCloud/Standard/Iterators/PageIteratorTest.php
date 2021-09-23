<?php

namespace Tests\TenantCloud\Standard\Iterators;

use TenantCloud\Standard\Iterators\PageIterator;

test('stops when empty', function () {
	expect(iterator_to_array(new PageIterator(fn () => [])))->toBe([]);
});

test('stops when empty on next', function () {
	expect(iterator_to_array(new PageIterator(function (int $page) {
		expect($page)->toBeGreaterThanOrEqual(1);
		expect($page)->toBeLessThanOrEqual(2);

		return $page === 1 ? ['a'] : [];
	})))->toBe(['a']);
});

test('returns all items one by one', function () {
	$itemsPerPage = [
		1 => ['a'],
		2 => ['a', 'b'],
		3 => ['c'],
		4 => [],
	];

	expect(iterator_to_array(
		new PageIterator(fn (int $page) => $itemsPerPage[$page]),
		false
	))->toBe(['a', 'a', 'b', 'c']);
});
