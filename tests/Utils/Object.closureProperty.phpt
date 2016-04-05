<?php

/**
 * Test: Nette\Object closure properties.
 */

use Tester\Assert;

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


test(function () {
	$obj = new TestClass(function ($a, $b) {
		return "$a $b";
	});

	Assert::same('1 2', $obj->public(1, 2));
});


test(function () {
	Assert::exception(function () {
		$obj = new TestClass(123);
		$obj->onPublic = function () {}; // accidentally forgotten []
		$obj->onPublic(1, 2);
	}, Nette\UnexpectedValueException::class, 'Property TestClass::$onPublic must be array or NULL, object given.');


	Assert::exception(function () {
		$obj = new TestClass(123);
		$obj->public();
	}, Nette\MemberAccessException::class, 'Call to undefined method TestClass::public().');


	Assert::exception(function () {
		$obj = new TestClass(function () {});
		$obj->protected();
	}, Nette\MemberAccessException::class, 'Call to undefined method TestClass::protected().');


	Assert::exception(function () {
		$obj = new TestClass(function () {});
		$obj->private();
	}, Nette\MemberAccessException::class, 'Call to undefined method TestClass::private().');
});
