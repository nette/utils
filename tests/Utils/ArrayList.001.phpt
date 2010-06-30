<?php

/**
 * Test: Nette\ArrayList basic usage.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayList;



require __DIR__ . '/../initialize.php';



class Person
{
	private $name;


	public function __construct($name)
	{
		$this->name = $name;
	}



	public function sayHi()
	{
		T::note("My name is $this->name");
	}



	public function __toString()
	{
		return $this->name;
	}
}



$list = new ArrayList;
$jack = new Person('Jack');
$mary = new Person('Mary');



T::note("Adding Mary");
$list[] = $mary;

T::note("Adding Jack");
$list[] = $jack;



T::dump( $list->count(), 'count:' );
T::dump( count($list) );


T::dump( iterator_to_array($list) );



T::note("Get Interator:");
foreach ($list as $key => $person) {
	echo $key, ' => ', $person->sayHi();
}



try {
	T::note("unset -1");
	unset($list[-1]);
} catch (Exception $e) {
	T::dump( $e );
}

try {
	T::note("unset 1");
	unset($list[1]);
} catch (Exception $e) {
	T::dump( $e );
}

T::dump( iterator_to_array($list) );



__halt_compiler() ?>

------EXPECT------
Adding Mary

Adding Jack

count: 2

2

array(
	Person(
		"name" private => "Mary"
	)
	Person(
		"name" private => "Jack"
	)
)

Get Interator:

0 => My name is Mary

1 => My name is Jack

unset -1

Exception OutOfRangeException: Offset invalid or out of range

unset 1

array(
	Person(
		"name" private => "Mary"
	)
)
