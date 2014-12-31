<?php

/**
 * Test: Nette\Utils\Callback closures tests.
 */

use Nette\Utils\Callback,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test
{
	function __invoke($a)
	{
		return __METHOD__ . $a;
	}

	public function publicFun($a)
	{
		return __METHOD__ . $a;
	}

	private function privateFun($a)
	{
		return __METHOD__ . $a;
	}

	public static function publicStatic($a)
	{
		return __METHOD__ . $a;
	}

	private static function privateStatic($a)
	{
		return __METHOD__ . $a;
	}

	public function __call($nm, $args)
	{
		return __METHOD__ . " $nm $args[0]";
	}

	public static function __callStatic($nm, $args)
	{
		return __METHOD__ . " $nm $args[0]";
	}

	public function ref(& $a)
	{
		$a = __METHOD__;
		return $a;
	}

}

class TestChild extends Test
{
}


function getName($ref)
{
	if ($ref instanceof ReflectionFunction) {
		return $ref->getName();
	} elseif ($ref instanceof ReflectionMethod) {
		return $ref->getDeclaringClass()->getName() . '::' . $ref->getName();
	}
}


test(function() { // global function
	Assert::same( 'trim', Callback::unwrap(Callback::closure('trim')) );
	Assert::same( 'trim', Callback::toString('trim') );
	Assert::same( '{closure trim}', Callback::toString(Callback::closure('trim')) );
	Assert::same( 'trim', getName(Callback::toReflection('trim')) );
	Assert::same( 'trim', getName(Callback::toReflection(Callback::closure('trim'))) );
	Assert::same( 'x', Callback::closure('trim')->__invoke(' x ') );


	Assert::same( 'undefined', Callback::toString('undefined') );

	Assert::exception(function() {
		Callback::closure('undefined');
	}, 'Nette\InvalidArgumentException', "Callback 'undefined' is not callable.");

	Assert::exception(function() {
		Callback::toReflection('undefined');
	}, 'ReflectionException', 'Function undefined() does not exist');
});


test(function() { // closure
	$closure = function(& $a) {
		$a = __FUNCTION__;
		return $a;
	};
	Assert::same( $closure, Callback::closure($closure) );
	Assert::same( $closure, Callback::unwrap($closure) );
	Assert::same( '{closure}', Callback::toString($closure) );
	Assert::same( '{closure}', getName(Callback::toReflection($closure)) );
	Assert::same( '{closure}', call_user_func_array(Callback::closure($closure), array(& $res)) );
	Assert::same( '{closure}', $res );
});


test(function() { // invokable object
	$test = new Test;
	Assert::same( array($test, '__invoke'), Callback::unwrap(Callback::closure($test)) );
	Assert::same( 'Test::__invoke', Callback::toString($test) );
	Assert::same( '{closure Test::__invoke}', Callback::toString(Callback::closure($test)) );
	Assert::same( 'Test::__invoke', getName(Callback::toReflection($test)) );
	Assert::same( 'Test::__invoke', getName(Callback::toReflection(Callback::closure($test))) );
	Assert::same( 'Test::__invoke*', Callback::closure($test)->__invoke('*') );
});


test(function() { // object methods
	$test = new Test;
	Assert::same( array($test, 'publicFun'), Callback::unwrap(Callback::closure($test, 'publicFun')) );
	Assert::same( array($test, 'publicFun'), Callback::unwrap(Callback::closure(array($test, 'publicFun'))) );

	Assert::same( 'Test::publicFun', Callback::toString(array($test, 'publicFun')) );
	Assert::same( '{closure Test::publicFun}', Callback::toString(Callback::closure($test, 'publicFun')) );

	Assert::same( 'Test::publicFun', getName(Callback::toReflection(array($test, 'publicFun'))) );
	Assert::same( 'Test::publicFun', getName(Callback::toReflection(Callback::closure($test, 'publicFun'))) );

	Assert::same( 'Test::publicFun*', Callback::closure($test, 'publicFun')->__invoke('*') );


	Assert::same( array($test, 'privateFun'), Callback::unwrap(Callback::closure($test, 'privateFun')) );
	Assert::same( array($test, 'privateFun'), Callback::unwrap(Callback::closure(array($test, 'privateFun'))) );

	Assert::same( 'Test::privateFun', Callback::toString(array($test, 'privateFun')) );
	Assert::same( '{closure Test::privateFun}', Callback::toString(Callback::closure($test, 'privateFun')) );

	Assert::same( 'Test::privateFun', getName(Callback::toReflection(array($test, 'privateFun'))) );
	Assert::same( 'Test::privateFun', getName(Callback::toReflection(Callback::closure($test, 'privateFun'))) );

	if (PHP_VERSION_ID < 50400) {
		Assert::same( 'Test::__call privateFun *', Callback::closure($test, 'privateFun')->__invoke('*') ); // not called!
	} else {
		Assert::same( 'Test::privateFun*', Callback::closure($test, 'privateFun')->__invoke('*') );

		Assert::same( 'Test::ref', call_user_func_array(Callback::closure($test, 'ref'), array(& $res)) );
		Assert::same( 'Test::ref', $res );
	}
});


test(function() { // static methods
	$test = new Test;
	Assert::same( array('Test', 'publicStatic'), Callback::unwrap(Callback::closure('Test', 'publicStatic')) );
	Assert::same( array('Test', 'publicStatic'), Callback::unwrap(Callback::closure(array('Test', 'publicStatic'))) );
	Assert::same( array('Test', 'publicStatic'), Callback::unwrap(Callback::closure('Test::publicStatic')) );

	Assert::same( 'Test::publicStatic', Callback::toString(array('Test', 'publicStatic')) );
	Assert::same( 'Test::publicStatic', Callback::toString(array($test, 'publicStatic')) );
	Assert::same( 'Test::publicStatic', Callback::toString('Test::publicStatic') );
	Assert::same( '{closure Test::publicStatic}', Callback::toString(Callback::closure('Test::publicStatic')) );

	Assert::same( 'Test::publicStatic', getName(Callback::toReflection(array('Test', 'publicStatic'))) );
	Assert::same( 'Test::publicStatic', getName(Callback::toReflection(array($test, 'publicStatic'))) );
	Assert::same( 'Test::publicStatic', getName(Callback::toReflection('Test::publicStatic')) );
	Assert::same( 'Test::publicStatic', getName(Callback::toReflection(Callback::closure('Test::publicStatic'))) );

	Assert::same( 'Test::publicStatic*', Callback::closure('Test', 'publicStatic')->__invoke('*') );
	Assert::same( 'Test::publicStatic*', Callback::closure($test, 'publicStatic')->__invoke('*') );


	Assert::same( array('Test', 'privateStatic'), Callback::unwrap(Callback::closure('Test::privateStatic')) );
	Assert::same( 'Test::privateStatic', Callback::toString('Test::privateStatic') );
	Assert::same( '{closure Test::privateStatic}', Callback::toString(Callback::closure('Test::privateStatic')) );
	Assert::same( 'Test::privateStatic', getName(Callback::toReflection('Test::privateStatic')) );
	Assert::same( 'Test::privateStatic', getName(Callback::toReflection(Callback::closure('Test::privateStatic'))) );

	if (PHP_VERSION_ID < 50400) {
		Assert::same( 'Test::__callStatic privateStatic *', Callback::closure('Test::privateStatic')->__invoke('*') ); // not called!
	} else {
		Assert::same( 'Test::privateStatic*', Callback::closure('Test::privateStatic')->__invoke('*') );
	}
});


test(function() { // magic methods
	$test = new Test;
	Assert::same( array($test, 'magic'), Callback::unwrap(Callback::closure($test, 'magic')) );
	Assert::same( 'Test::magic', Callback::toString(array($test, 'magic')) );
	Assert::same( '{closure Test::magic}', Callback::toString(Callback::closure($test, 'magic')) );
	Assert::same( 'Test::__call magic *', Callback::closure($test, 'magic')->__invoke('*') );

	Assert::same( array('Test', 'magic'), Callback::unwrap(Callback::closure('Test::magic')) );
	Assert::same( 'Test::magic', Callback::toString('Test::magic') );
	Assert::same( '{closure Test::magic}', Callback::toString(Callback::closure('Test::magic')) );
	Assert::same( 'Test::__callStatic magic *', Callback::closure('Test::magic')->__invoke('*') );

	Assert::exception(function() {
		Callback::toReflection(array(new Test, 'magic'));
	}, 'ReflectionException', 'Method Test::magic() does not exist');

	Assert::exception(function() {
		Callback::toReflection(Callback::closure(new Test, 'magic'));
	}, 'ReflectionException', 'Method Test::magic() does not exist');
});
