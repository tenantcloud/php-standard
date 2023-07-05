<?php

use TenantCloud\Standard\Iterators\ArrayValuesIterator;

test('iterates over empty instance', function () {
	expect(iterator_to_array(new ArrayValuesIterator()))->toBe([]);
});

test('iterates over default items', function () {
	expect(iterator_to_array(new ArrayValuesIterator([1, 2, 10])))->toBe([1, 2, 10]);
});

test('merges items into an empty instance', function () {
	$iterator = new ArrayValuesIterator();

	$iterator->merge([2, 3], 0);

	expect(iterator_to_array($iterator))->toBe([2, 3]);
});

test('merges items into an empty instance at a higher position than the end', function () {
	$iterator = new ArrayValuesIterator();

	$iterator->merge([1, 3], 10);

	expect(iterator_to_array($iterator))->toBe([1, 3]);
});

test('merges items at the start', function () {
	$iterator = new ArrayValuesIterator([4, 5]);

	$iterator->merge([1, 3], 0);

	expect(iterator_to_array($iterator))->toBe([1, 3, 4, 5]);
});

test('merges items in the middle', function () {
	$iterator = new ArrayValuesIterator([4, 5, 6]);

	$iterator->merge([1, 3], 2);

	expect(iterator_to_array($iterator))->toBe([4, 5, 1, 3, 6]);
});

test('merges items in the process', function () {
	$addTimes = 3;

	$iterator = new ArrayValuesIterator([1, 2, 3]);

	$expected = [1, 2, 3, 4, 2, 3];
	$i = 0;

	foreach ($iterator as $value) {
		if ($addTimes > 0) {
			$iterator->merge([$value + 1], $iterator->key() + 1);

			$addTimes--;
		}

		expect($value)->toBe($expected[$i]);

		$i++;
	}

	expect($i)->toBe(6);
});
