<?php

/**
 * Test: Nette\Utils\Callback closures tests.
 */

declare(strict_types=1);

use Nette\Utils\Callback;
use Tester\Assert;


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

	public function ref(&$a)
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


test(function () { // global function
	Assert::same('trim', Callback::unwrap(Callback::closure('trim')));
	Assert::same('trim', Callback::toString('trim'));
	Assert::same('{closure trim}', Callback::toString(Callback::closure('trim')));
	Assert::same('trim', getName(Callback::toReflection('trim')));
	Assert::same('trim', getName(Callback::toReflection(Callback::closure('trim'))));
	Assert::same('x', Callback::closure('trim')->__invoke(' x '));


	Assert::same('undefined', Callback::toString('undefined'));

	Assert::exception(function () {
		Callback::closure('undefined');
	}, Nette\InvalidArgumentException::class, "Callback 'undefined' is not callable.");

	Assert::exception(function () {
		Callback::toReflection('undefined');
	}, ReflectionException::class, 'Function undefined() does not exist');
});


test(function () { // closure
	$closure = function (&$a) {
		$a = __FUNCTION__;
		return $a;
	};
	Assert::same($closure, Callback::closure($closure));
	Assert::same($closure, Callback::unwrap($closure));
	Assert::same('{closure}', Callback::toString($closure));
	Assert::same('{closure}', getName(Callback::toReflection($closure)));
	Assert::same('{closure}', Callback::closure($closure)(...[&$res]));
	Assert::same('{closure}', $res);
});


test(function () { // invokable object
	$test = new Test;
	Assert::same([$test, '__invoke'], Callback::unwrap(Callback::closure($test)));
	Assert::same('Test::__invoke', Callback::toString($test));
	Assert::same('{closure Test::__invoke}', Callback::toString(Callback::closure($test)));
	Assert::same('Test::__invoke', getName(Callback::toReflection($test)));
	Assert::same('Test::__invoke', getName(Callback::toReflection(Callback::closure($test))));
	Assert::same('Test::__invoke*', Callback::closure($test)->__invoke('*'));
});


test(function () { // object methods
	$test = new Test;
	Assert::same([$test, 'publicFun'], Callback::unwrap(Callback::closure($test, 'publicFun')));
	Assert::same([$test, 'publicFun'], Callback::unwrap(Callback::closure([$test, 'publicFun'])));

	Assert::same('Test::publicFun', Callback::toString([$test, 'publicFun']));
	Assert::same('{closure Test::publicFun}', Callback::toString(Callback::closure($test, 'publicFun')));

	Assert::same('Test::publicFun', getName(Callback::toReflection([$test, 'publicFun'])));
	Assert::same('Test::publicFun', getName(Callback::toReflection(Callback::closure($test, 'publicFun'))));

	Assert::same('Test::publicFun*', Callback::closure($test, 'publicFun')->__invoke('*'));


	Assert::same([$test, 'privateFun'], Callback::unwrap(Callback::closure($test, 'privateFun')));
	Assert::same([$test, 'privateFun'], Callback::unwrap(Callback::closure([$test, 'privateFun'])));

	Assert::same('Test::privateFun', Callback::toString([$test, 'privateFun']));
	Assert::same('{closure Test::privateFun}', Callback::toString(Callback::closure($test, 'privateFun')));

	Assert::same('Test::privateFun', getName(Callback::toReflection([$test, 'privateFun'])));
	Assert::same('Test::privateFun', getName(Callback::toReflection(Callback::closure($test, 'privateFun'))));

	Assert::same('Test::privateFun*', Callback::closure($test, 'privateFun')->__invoke('*'));

	Assert::same('Test::ref', Callback::closure($test, 'ref')(...[&$res]));
	Assert::same('Test::ref', $res);
});


test(function () { // static methods
	$test = new Test;
	Assert::same(['Test', 'publicStatic'], Callback::unwrap(Callback::closure('Test', 'publicStatic')));
	Assert::same(['Test', 'publicStatic'], Callback::unwrap(Callback::closure(['Test', 'publicStatic'])));
	Assert::same(['Test', 'publicStatic'], Callback::unwrap(Callback::closure('Test::publicStatic')));

	Assert::same('Test::publicStatic', Callback::toString(['Test', 'publicStatic']));
	Assert::same('Test::publicStatic', Callback::toString([$test, 'publicStatic']));
	Assert::same('Test::publicStatic', Callback::toString('Test::publicStatic'));
	Assert::same('{closure Test::publicStatic}', Callback::toString(Callback::closure('Test::publicStatic')));

	Assert::same('Test::publicStatic', getName(Callback::toReflection(['Test', 'publicStatic'])));
	Assert::same('Test::publicStatic', getName(Callback::toReflection([$test, 'publicStatic'])));
	Assert::same('Test::publicStatic', getName(Callback::toReflection('Test::publicStatic')));
	Assert::same('Test::publicStatic', getName(Callback::toReflection(Callback::closure('Test::publicStatic'))));

	Assert::same('Test::publicStatic*', Callback::closure('Test', 'publicStatic')->__invoke('*'));
	Assert::same('Test::publicStatic*', Callback::closure($test, 'publicStatic')->__invoke('*'));


	Assert::same(['Test', 'privateStatic'], Callback::unwrap(Callback::closure('Test::privateStatic')));
	Assert::same('Test::privateStatic', Callback::toString('Test::privateStatic'));
	Assert::same('{closure Test::privateStatic}', Callback::toString(Callback::closure('Test::privateStatic')));
	Assert::same('Test::privateStatic', getName(Callback::toReflection('Test::privateStatic')));
	Assert::same('Test::privateStatic', getName(Callback::toReflection(Callback::closure('Test::privateStatic'))));

	Assert::same('Test::privateStatic*', Callback::closure('Test::privateStatic')->__invoke('*'));
});


test(function () { // magic methods
	$test = new Test;
	Assert::same([$test, 'magic'], Callback::unwrap(Callback::closure($test, 'magic')));
	Assert::same('Test::magic', Callback::toString([$test, 'magic']));
	Assert::same('{closure Test::magic}', Callback::toString(Callback::closure($test, 'magic')));
	Assert::same('Test::__call magic *', Callback::closure($test, 'magic')->__invoke('*'));

	Assert::same(['Test', 'magic'], Callback::unwrap(Callback::closure('Test::magic')));
	Assert::same('Test::magic', Callback::toString('Test::magic'));
	Assert::same('{closure Test::magic}', Callback::toString(Callback::closure('Test::magic')));
	Assert::same('Test::__callStatic magic *', Callback::closure('Test::magic')->__invoke('*'));

	Assert::exception(function () {
		Callback::toReflection([new Test, 'magic']);
	}, ReflectionException::class, 'Method Test::magic() does not exist');

	Assert::exception(function () {
		Callback::toReflection(Callback::closure(new Test, 'magic'));
	}, ReflectionException::class, 'Method Test::magic() does not exist');
});
