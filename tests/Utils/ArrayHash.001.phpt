<?php

/**
 * Test: Nette\ArrayHash basic usage.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
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



	public function __toString()
	{
		return $this->name;
	}
}



$list = new ArrayHash;
$jack = new Person('Jack');
$mary = new Person('Mary');



// Adding Mary
$list['m'] = $mary;

// Adding Jack
$list['j'] = $jack;



Assert::same( 2, $list->count(), 'count:' );

Assert::same( 2, count($list) );



Assert::equal( array(
	'm' => new Person('Mary'),
	'j' => new Person('Jack'),
), iterator_to_array($list) );



Assert::equal( array(
	'm' => new Person('Mary'),
	'j' => new Person('Jack'),
), (array) $list );




// Get Interator:
foreach ($list as $key => $person) {
	$tmp[] = $key . ' => ' . $person->sayHi();
}
Assert::same( array(
	'm => My name is Mary',
	'j => My name is Jack',
), $tmp );




unset($list['j']);
Assert::equal( array(
	'm' => new Person('Mary'),
), iterator_to_array($list) );
