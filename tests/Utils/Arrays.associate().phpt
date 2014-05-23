<?php

/**
 * Test: Nette\Utils\Arrays::associate()
 */

use Nette\Utils\Arrays,
	Nette\Utils\DateTime,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr = array(
	array('name' => 'John', 'age' => 11),
	array('name' => 'John', 'age' => 22),
	array('name' => 'Mary', 'age' => NULL),
	array('name' => 'Paul', 'age' => 44),
);


Assert::same(
	array(
		'John' => array('name' => 'John', 'age' => 11),
		'Mary' => array('name' => 'Mary', 'age' => NULL),
		'Paul' => array('name' => 'Paul', 'age' => 44),
	),
	Arrays::associate($arr, 'name')
);

Assert::same(
	array(),
	Arrays::associate(array(), 'name')
);

Assert::same(
	array(
		'John' => array('name' => 'John', 'age' => 11),
		'Mary' => array('name' => 'Mary', 'age' => NULL),
		'Paul' => array('name' => 'Paul', 'age' => 44),
	),
	Arrays::associate($arr, 'name=')
);

Assert::same(
	array('John' => 22, 'Mary' => NULL, 'Paul' => 44),
	Arrays::associate($arr, 'name=age')
);

Assert::same( // path as array
	array('John' => 22, 'Mary' => NULL, 'Paul' => 44),
	Arrays::associate($arr, array('name', '=', 'age'))
);

Assert::equal(
	array(
		'John' => (object) array(
			'name' => 'John',
			'age' => 11,
		),
		'Mary' => (object) array(
			'name' => 'Mary',
			'age' => NULL,
		),
		'Paul' => (object) array(
			'name' => 'Paul',
			'age' => 44,
		),
	),
	Arrays::associate($arr, 'name->')
);

Assert::equal(
	array(
		11 => (object) array(
			'John' => array('name' => 'John', 'age' => 11),
		),
		22 => (object) array(
			'John' => array('name' => 'John', 'age' => 22),
		),
		NULL => (object) array(
			'Mary' => array('name' => 'Mary', 'age' => NULL),
		),
		44 => (object) array(
			'Paul' => array('name' => 'Paul', 'age' => 44),
		),
	),
	Arrays::associate($arr, 'age->name')
);

Assert::equal(
	(object) array(
		'John' => array('name' => 'John', 'age' => 11),
		'Mary' => array('name' => 'Mary', 'age' => NULL),
		'Paul' => array('name' => 'Paul', 'age' => 44),
	),
	Arrays::associate($arr, '->name')
);

Assert::equal(
	(object) array(),
	Arrays::associate(array(), '->name')
);

Assert::same(
	array(
		'John' => array(
			11 => array('name' => 'John', 'age' => 11),
			22 => array('name' => 'John', 'age' => 22),
		),
		'Mary' => array(
			NULL => array('name' => 'Mary', 'age' => NULL),
		),
		'Paul' => array(
			44 => array('name' => 'Paul', 'age' => 44),
		),
	),
	Arrays::associate($arr, 'name|age')
);

Assert::same(
	array(
		'John' => array('name' => 'John', 'age' => 11),
		'Mary' => array('name' => 'Mary', 'age' => NULL),
		'Paul' => array('name' => 'Paul', 'age' => 44),
	),
	Arrays::associate($arr, 'name|')
);

Assert::same(
	array(
		'John' => array(
			array('name' => 'John', 'age' => 11),
			array('name' => 'John', 'age' => 22),
		),
		'Mary' => array(
			array('name' => 'Mary', 'age' => NULL),
		),
		'Paul' => array(
			array('name' => 'Paul', 'age' => 44),
		),
	),
	Arrays::associate($arr, 'name[]')
);

Assert::same(
	array(
		array('John' => array('name' => 'John', 'age' => 11)),
		array('John' => array('name' => 'John', 'age' => 22)),
		array('Mary' => array('name' => 'Mary', 'age' => NULL)),
		array('Paul' => array('name' => 'Paul', 'age' => 44)),
	),
	Arrays::associate($arr, '[]name')
);

Assert::same(
	array('John', 'John', 'Mary', 'Paul'),
	Arrays::associate($arr, '[]=name')
);

Assert::same(
	array(
		'John' => array(
			array(11 => array('name' => 'John', 'age' => 11)),
			array(22 => array('name' => 'John', 'age' => 22)),
		),
		'Mary' => array(
			array(NULL => array('name' => 'Mary', 'age' => NULL)),
		),
		'Paul' => array(
			array(44 => array('name' => 'Paul', 'age' => 44)),
		),
	),
	Arrays::associate($arr, 'name[]age')
);

Assert::same(
	$arr,
	Arrays::associate($arr, '[]')
);

// converts object to array
Assert::same(
	$arr,
	Arrays::associate($arr = array(
		(object) array('name' => 'John', 'age' => 11),
		(object) array('name' => 'John', 'age' => 22),
		(object) array('name' => 'Mary', 'age' => NULL),
		(object) array('name' => 'Paul', 'age' => 44),
	), '[]')
);

// allowes objects in keys
Assert::equal(
	array('2014-02-05 00:00:00' => new DateTime('2014-02-05')),
	Arrays::associate($arr = array(
		array('date' => new DateTime('2014-02-05')),
	), 'date=date')
);
Assert::equal(
	(object) array('2014-02-05 00:00:00' => new DateTime('2014-02-05')),
	Arrays::associate($arr = array(
		array('date' => new DateTime('2014-02-05')),
	), '->date=date')
);
