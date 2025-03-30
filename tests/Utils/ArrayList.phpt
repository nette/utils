<?php

/**
 * Test: Nette\Utils\ArrayList basic usage.
 */

declare(strict_types=1);

use Nette\Utils\ArrayList;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Person
{
	private $name;


	public function __construct($name)
	{
		$this->name = $name;
	}


	public function sayHi()
	{
		return "My name is $this->name";
	}
}


test('creation from valid list and error on associative arrays', function () {
	Assert::exception(
		fn() => ArrayList::from(['a' => 1, 'b' => 2]),
		Nette\InvalidArgumentException::class,
		'Array is not valid list.',
	);

	$mary = new Person('Mary');
	$list = ArrayList::from([$mary, 'Jack']);

	Assert::type(Nette\Utils\ArrayList::class, $list);
	Assert::same([$mary, 'Jack'], iterator_to_array($list));
});


test('supports push, access, iteration, unset, and prepend operations', function () {
	$list = new ArrayList;
	$jack = new Person('Jack');
	$mary = new Person('Mary');

	$list[] = $mary;
	$list[] = $jack;

	Assert::same($mary, $list[0]);
	Assert::same($jack, $list[1]);

	Assert::true(isset($list[0]));
	Assert::false(isset($list[500]));
	Assert::false(isset($list['fake']));

	Assert::same([
		$mary,
		$jack,
	], iterator_to_array($list));


	foreach ($list as $key => $person) {
		$tmp[] = $key . ' => ' . $person->sayHi();
	}

	Assert::same([
		'0 => My name is Mary',
		'1 => My name is Jack',
	], $tmp);


	Assert::same(2, $list->count());
	Assert::same(2, count($list));

	unset($list[1]);
	Assert::same([
		$mary,
	], iterator_to_array($list));

	$list->prepend('First');
	Assert::same('First', $list[0], 'Value "First" should be on the start of the array');
});


test('setting invalid offsets throws OutOfRangeException', function () {
	$list = new ArrayList;
	$list[] = 'a';
	$list[] = 'b';

	Assert::exception(
		fn() => $list[-1] = true,
		OutOfRangeException::class,
		'Offset invalid or out of range',
	);

	Assert::exception(
		fn() => $list[2] = true,
		OutOfRangeException::class,
		'Offset invalid or out of range',
	);

	Assert::exception(
		fn() => $list['key'] = true,
		OutOfRangeException::class,
		'Offset invalid or out of range',
	);
});


test('accessing invalid offsets throws OutOfRangeException', function () {
	$list = new ArrayList;
	$list[] = 'a';
	$list[] = 'b';

	Assert::exception(
		fn() => $list[-1],
		OutOfRangeException::class,
		'Offset invalid or out of range',
	);

	Assert::exception(
		fn() => $list[2],
		OutOfRangeException::class,
		'Offset invalid or out of range',
	);

	Assert::exception(
		fn() => $list['key'],
		OutOfRangeException::class,
		'Offset invalid or out of range',
	);
});


test('unsetting invalid offsets throws OutOfRangeException', function () {
	$list = new ArrayList;
	$list[] = 'a';
	$list[] = 'b';

	Assert::exception(function () use ($list) {
		unset($list[-1]);
	}, OutOfRangeException::class, 'Offset invalid or out of range');

	Assert::exception(function () use ($list) {
		unset($list[2]);
	}, OutOfRangeException::class, 'Offset invalid or out of range');

	Assert::exception(function () use ($list) {
		unset($list['key']);
	}, OutOfRangeException::class, 'Offset invalid or out of range');
});


test('reference iteration modifies all list elements', function () {
	$list = ArrayList::from([1, 2, 3]);
	foreach ($list as $key => &$value) {
		$value = 'new';
	}

	Assert::same(['new', 'new', 'new'], iterator_to_array($list));
});
