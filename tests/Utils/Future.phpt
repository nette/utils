<?php

/**
 * Test: Nette\Utils\Future test.
 */

declare(strict_types=1);

use Nette\Utils\Future;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$resolver = function (array $keys): array {
	$result = [];
	foreach ($keys as $key) {
		$result[$key] = match ($key) {
			1 => 'one',
			'a' => 'AAA',
		};
	}
	return $result;
};


test('bind()', function () use ($resolver) {
	(new Future($resolver))
		->bind(1, $n)
		->bind('a', $a)
		->resolve();

	Assert::same('one', $n);
	Assert::same('AAA', $a);
});


test('bindVar()', function () use ($resolver) {
	$n = 1;
	$a = 'a';

	(new Future($resolver))
		->bindVar($n)
		->bindVar($a)
		->resolve();

	Assert::same('one', $n);
	Assert::same('AAA', $a);
});


test('bindArrayValues()', function () use ($resolver) {
	$a = [1, 'a'];

	(new Future($resolver))
		->bindArrayValues($a)
		->resolve();

	Assert::same(['one', 'AAA'], $a);;
});


test('bindArrayKeys()', function () use ($resolver) {
	$a = [
		1 => null,
		'a' => null,
	];

	(new Future($resolver))
		->bindArrayKeys($a)
		->resolve();

	Assert::same([
		1 => 'one',
		'a' => 'AAA',
	], $a);
});


test('bindArraysKey()', function () use ($resolver) {
	$a = [
		['k' => 1],
		['k' => 'a'],
	];

	(new Future($resolver))
		->bindArraysKey('k', $a)
		->resolve();

	Assert::same([
		['k' => 'one'],
		['k' => 'AAA'],
	], $a);
});


test('bindObjectsProperty()', function () use ($resolver) {
	$a = [
		(object) ['p' => 1],
		(object) ['p' => 'a'],
	];

	(new Future($resolver))
		->bindObjectsProperty('p', $a)
		->resolve();

	Assert::equal([
		(object) ['p' => 'one'],
		(object) ['p' => 'AAA'],
	], $a);
});


test('Resolver returns Traversable.', function () {
	(new Future(fn (array $keys) => new \ArrayIterator([1 => 'one'])))
		->bind(1, $n)
		->resolve();

	Assert::same('one', $n);
});



test('bindArraysKey() invalid item', function () {
	Assert::throws(function () {
		$a = [
			123  # this should be an array
		];

		(new Future(fn() => null))
			->bindArraysKey('k', $a)
			->resolve();

	}, \AssertionError::class, 'assert(is_array($item))');
});


test('bindObjectsProperty() invalid item', function () {
	Assert::throws(function () {
		$a = [
			123  # this should be an object
		];

		(new Future(fn() => null))
			->bindObjectsProperty('p', $a)
			->resolve();

	}, \AssertionError::class, 'assert(is_object($item))');
});


test('Invalid resolver return type.', function () {
	Assert::throws(function () {
		(new Future(fn() => null))
			->bind(1, $n)
			->resolve();

	}, Nette\UnexpectedValueException::class, "Resolver returned 'null' but array or Traversable expected.");
});


test('Resolver does not return all values.', function () {
	$a = 123;
	$b = 456;
	$c = 789;

	try {
		(new Future(fn() => ['a' => 'AAA', 'c' => 'CCC']))
			->bind('a', $a)
			->bind('b', $b)
			->bind('c', $c)
			->resolve();

		throw new Exception('Should not be there.');

	} catch (Nette\Utils\FutureException $e) {
		Assert::same($e->getMessage(), 'Resolver did not return required items.');
	}

	Assert::same(['b'], $e->getMissingKeys());

	# Bound variables stay unchanged
	Assert::same(123, $a);
	Assert::same(456, $b);
	Assert::same(789, $c);
});


test('Default value for missing resolver items.', function () {
	$a = 123;
	$b = 456;
	$c = 789;

	(new Future(fn() => ['a' => 'AAA', 'c' => 'CCC']))
		->setDefaultValueFactory(fn($key) => "DEFAULT-$key")
		->bind('a', $a)
		->bind('b', $b)
		->bind('c', $c)
		->resolve();


	Assert::same('AAA', $a);
	Assert::same('DEFAULT-b', $b);
	Assert::same('CCC', $c);
});


test('Resolver is not called when no variable is bound.', function () {
	$resolver = function () {
		throw new Exception('Should not be called.');
	};

	(new Future($resolver))
		->resolve();
});
