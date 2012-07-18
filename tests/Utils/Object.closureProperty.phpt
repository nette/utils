<?php

/**
 * Test: Nette\Object closure properties.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 * @phpversion 5.3
 */




require __DIR__ . '/../bootstrap.php';



class TestClass extends Nette\Object
{
	public $public;
	public $onPublic;
	protected $protected;
	private $private;

	function __construct($func)
	{
		$this->public = $this->onPublic = $this->protected = $this->private = $func;
	}

}



$obj = new TestClass(function($a, $b) {
	return "$a $b";
});

Assert::same( "1 2", $obj->public(1, 2) );
Assert::same( "1 2", $obj->onPublic(1, 2) );


Assert::throws(function() {
	$obj = new TestClass(123);
	$obj->public();
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::public().');


Assert::throws(function() {
	$obj = new TestClass(function(){});
	$obj->protected();
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::protected().');


Assert::throws(function() {
	$obj = new TestClass(function(){});
	$obj->private();
}, 'Nette\MemberAccessException', 'Call to undefined method TestClass::private().');
