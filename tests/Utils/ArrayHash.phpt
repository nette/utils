<?php

/**
 * Test: Nette\Utils\ArrayHash basic usage.
 */

declare(strict_types=1);

use Nette\Utils\ArrayHash;
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


test('store and retrieve values via array and property notation', function () {
	$list = new ArrayHash;
	$jack = new Person('Jack');
	$mary = new Person('Mary');

	$list['m'] = $mary;
	$list['j'] = $jack;

	Assert::same($mary, $list['m']);
	Assert::same($jack, $list['j']);

	Assert::same($mary, $list->m);
	Assert::same($jack, $list->j);


	Assert::same([
		'm' => $mary,
		'j' => $jack,
	], iterator_to_array($list));


	Assert::same([
		'm' => $mary,
		'j' => $jack,
	], (array) $list);


	foreach ($list as $key => $person) {
		$tmp[] = $key . ' => ' . $person->sayHi();
	}

	Assert::same([
		'm => My name is Mary',
		'j => My name is Jack',
	], $tmp);


	Assert::same(2, $list->count());
	Assert::same(2, count($list));


	unset($list['j']);
	Assert::same([
		'm' => $mary,
	], iterator_to_array($list));
});


test('creation with non‐recursive conversion leaves nested arrays unchanged', function () {
	$mary = new Person('Mary');
	$list = ArrayHash::from([
		'm' => $mary,
		'j' => 'Jack',
		'children' => [
			'c' => 'John',
		],
	], recursive: false);
	Assert::type(Nette\Utils\ArrayHash::class, $list);
	Assert::type('array', $list['children']);
});


test('recursive conversion transforms nested arrays into ArrayHash', function () {
	$mary = new Person('Mary');
	$list = ArrayHash::from([
		'm' => $mary,
		'j' => 'Jack',
		'children' => [
			'c' => 'John',
		],
	]);
	Assert::type(Nette\Utils\ArrayHash::class, $list);
	Assert::same($mary, $list['m']);
	Assert::same('Jack', $list['j']);
	Assert::type(Nette\Utils\ArrayHash::class, $list['children']);
	Assert::same('John', $list['children']['c']);

	$list['children']['c'] = 'Jim';
	Assert::same('Jim', $list['children']['c']);


	Assert::same([
		'm' => $mary,
		'j' => 'Jack',
		'children' => $list['children'],
		'c' => 'Jim',
	], iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($list), RecursiveIteratorIterator::SELF_FIRST)));
});


test('numeric key handling supports both integer and string offsets', function () {
	$row = ArrayHash::from([1, 2]);

	foreach ($row as $key => $value) {
		$keys[] = $key;
	}

	if (PHP_VERSION_ID < 70200) {
		Assert::same(['0', '1'], $keys);
	} else {
		Assert::same([0, 1], $keys);
	}

	Assert::same(1, $row->{0});
	Assert::same(1, $row->{'0'});
	Assert::same(1, $row[0]);
	Assert::same(1, $row['0']);
	Assert::true(isset($row->{0}));
	Assert::true(isset($row->{'0'}));
	Assert::true(isset($row[0]));
	Assert::true(isset($row['0']));

	Assert::same(2, $row->{1});
	Assert::same(2, $row->{'1'});
	Assert::same(2, $row[1]);
	Assert::same(2, $row['1']);
	Assert::true(isset($row->{1}));
	Assert::true(isset($row->{'1'}));
	Assert::true(isset($row[1]));
	Assert::true(isset($row['1']));

	Assert::false(isset($row->{2}));
	Assert::false(isset($row->{'2'}));
	Assert::false(isset($row[2]));
	Assert::false(isset($row['2']));

	$row[3] = 'new';
	Assert::same('new', $row->{3});
	Assert::same('new', $row->{'3'});
	Assert::same('new', $row[3]);
	Assert::same('new', $row['3']);

	unset($row[3]);
	Assert::false(isset($row->{3}));
	Assert::false(isset($row->{'3'}));
	Assert::false(isset($row[3]));
	Assert::false(isset($row['3']));
});


test('null values are stored but not regarded as set', function () {
	$row = ArrayHash::from(['null' => null]);
	Assert::null($row->null);
	Assert::null($row['null']);
	Assert::false(isset($row->null));
	Assert::false(isset($row['null']));
});


test('accessing undefined keys triggers a notice or warning', function () {
	$row = new ArrayHash;
	Assert::error(
		fn() => $row->undef,
		PHP_VERSION_ID < 80000 ? E_NOTICE : E_WARNING,
		'Undefined property: Nette\Utils\ArrayHash::$undef',
	);

	Assert::error(
		fn() => $row['undef'],
		PHP_VERSION_ID < 80000 ? E_NOTICE : E_WARNING,
		'Undefined property: Nette\Utils\ArrayHash::$undef',
	);
});


test('unsetting entries during iteration removes them', function () {
	$hash = ArrayHash::from([1, 2, 3]);
	foreach ($hash as $key => $value) {
		unset($hash->$key);
	}

	Assert::count(0, $hash);
});


test('reference iteration allows modification of all elements', function () {
	$hash = ArrayHash::from([1, 2, 3]);
	foreach ($hash as $key => &$value) {
		$value = 'new';
	}

	Assert::same(['new', 'new', 'new'], (array) $hash);
});
