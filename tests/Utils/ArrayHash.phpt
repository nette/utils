<?php

/**
 * Test: Nette\ArrayHash basic usage.
 *
 * @author     David Grudl
 * @package    Nette
 */

use Nette\ArrayHash;


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


test(function() {
	$list = new ArrayHash;
	$jack = new Person('Jack');
	$mary = new Person('Mary');

	$list['m'] = $mary;
	$list['j'] = $jack;

	Assert::same( $mary, $list['m'] );
	Assert::same( $jack, $list['j'] );

	Assert::same( $mary, $list->m );
	Assert::same( $jack, $list->j );


	Assert::same( array(
		'm' => $mary,
		'j' => $jack,
	), iterator_to_array($list) );


	Assert::same( array(
		'm' => $mary,
		'j' => $jack,
	), (array) $list );


	foreach ($list as $key => $person) {
		$tmp[] = $key . ' => ' . $person->sayHi();
	}
	Assert::same( array(
		'm => My name is Mary',
		'j => My name is Jack',
	), $tmp );


	Assert::same( 2, $list->count() );
	Assert::same( 2, count($list) );


	unset($list['j']);
	Assert::same( array(
		'm' => $mary,
	), iterator_to_array($list) );
});


test(function() {
	$mary = new Person('Mary');
	$list = ArrayHash::from(array(
		'm' => $mary,
		'j' => 'Jack',
		'children' => array(
			'c' => 'John',
		),
	), FALSE);
	Assert::type( 'Nette\ArrayHash', $list );
	Assert::type( 'array', $list['children'] );
});


test(function() {
	$mary = new Person('Mary');
	$list = ArrayHash::from(array(
		'm' => $mary,
		'j' => 'Jack',
		'children' => array(
			'c' => 'John',
		),
	));
	Assert::type( 'Nette\ArrayHash', $list );
	Assert::same( $mary, $list['m'] );
	Assert::same( 'Jack', $list['j'] );
	Assert::type( 'Nette\ArrayHash', $list['children'] );
	Assert::same( 'John', $list['children']['c'] );

	$list['children']['c'] = 'Jim';
	Assert::same( 'Jim', $list['children']['c'] );


	Assert::same( array(
		'm' => $mary,
		'j' => 'Jack',
		'children' => $list['children'],
		'c' => 'Jim',
	), iterator_to_array(new RecursiveIteratorIterator($list, RecursiveIteratorIterator::SELF_FIRST)) );
});
