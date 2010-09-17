<?php

/**
 * Test: Nette\ArrayList basic usage.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayList;



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



	public function __toString()
	{
		return $this->name;
	}
}



$list = new ArrayList;
$jack = new Person('Jack');
$mary = new Person('Mary');



// Adding Mary
$list[] = $mary;

// Adding Jack
$list[] = $jack;



Assert::same( 2, $list->count(), 'count:' );

Assert::same( 2, count($list) );



Assert::equal( array(
	new Person('Mary'),
	new Person('Jack'),
), iterator_to_array($list) );




// Get Interator:
foreach ($list as $key => $person) {
	$tmp[] = $key . ' => ' . $person->sayHi();
}
Assert::same( array(
	'0 => My name is Mary',
	'1 => My name is Jack',
), $tmp );




try {
	// unset -1
	unset($list[-1]);
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('OutOfRangeException', 'Offset invalid or out of range', $e );
}

unset($list[1]);
Assert::equal( array(
	new Person('Mary'),
), iterator_to_array($list) );
