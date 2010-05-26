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



require __DIR__ . '/../NetteTest/initialize.php';



class Person
{
	private $name;


	public function __construct($name)
	{
		$this->name = $name;
	}



	public function sayHi()
	{
		output("My name is $this->name");
	}



	public function __toString()
	{
		return $this->name;
	}
}



$list = new ArrayList;
$jack = new Person('Jack');
$mary = new Person('Mary');



output("Adding Mary");
$list[] = $mary;

output("Adding Jack");
$list[] = $jack;



dump( $list->count(), 'count:' );
dump( count($list) );


dump( iterator_to_array($list) );



output("Get Interator:");
foreach ($list as $key => $person) {
	echo $key, ' => ', $person->sayHi();
}



try {
	output("unset -1");
	unset($list[-1]);
} catch (Exception $e) {
	dump( $e );
}

try {
	output("unset 1");
	unset($list[1]);
} catch (Exception $e) {
	dump( $e );
}

dump( iterator_to_array($list) );



__halt_compiler() ?>

------EXPECT------
Adding Mary

Adding Jack

count: int(2)

int(2)

array(2) {
	0 => object(Person) (1) {
		"name" private => string(4) "Mary"
	}
	1 => object(Person) (1) {
		"name" private => string(4) "Jack"
	}
}

Get Interator:

0 => My name is Mary

1 => My name is Jack

unset -1

Exception OutOfRangeException: Offset invalid or out of range

unset 1

array(1) {
	0 => object(Person) (1) {
		"name" private => string(4) "Mary"
	}
}
