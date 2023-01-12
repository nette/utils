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
	public function __invoke($a)
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


	public function createPrivateClosure(): Closure
	{
		return Closure::fromCallable([$this, 'privateFun']);
	}


	public static function createPrivateStaticClosure(): Closure
	{
		return Closure::fromCallable([self::class, 'privateStatic']);
	}


	public function ref(&$a)
	{
		$a = __METHOD__;
		return $a;
	}
}


class TestDynamic
{
	public function __call($nm, $args)
	{
		return __METHOD__ . " $nm $args[0]";
	}


	public static function __callStatic($nm, $args)
	{
		return __METHOD__ . " $nm $args[0]";
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


test('global function', function () {
	Assert::same('trim', Callback::unwrap(Closure::fromCallable('trim')));
	Assert::same('trim', Callback::toString('trim'));
	Assert::same('{closure trim}', Callback::toString(Closure::fromCallable('trim')));
	Assert::same('trim', getName(Callback::toReflection('trim')));
	Assert::same('trim', getName(Callback::toReflection(Closure::fromCallable('trim'))));
	Assert::same('x', Closure::fromCallable('trim')->__invoke(' x '));


	Assert::same('undefined', Callback::toString('undefined'));

	Assert::exception(
		fn() => Callback::toReflection('undefined'),
		ReflectionException::class,
		'Function undefined() does not exist',
	);
});


test('closure', function () {
	$closure = function (&$a) {
		$a = __FUNCTION__;
		return $a;
	};
	Assert::same($closure, Closure::fromCallable($closure));
	Assert::same($closure, Callback::unwrap($closure));
	Assert::same('{closure}', Callback::toString($closure));
	Assert::same('{closure}', getName(Callback::toReflection($closure)));
	Assert::same('{closure}', Closure::fromCallable($closure)(...[&$res]));
	Assert::same('{closure}', $res);
});


test('invokable object', function () {
	$test = new Test;
	Assert::same([$test, '__invoke'], Callback::unwrap(Closure::fromCallable($test)));
	Assert::same('Test::__invoke', Callback::toString($test));
	Assert::same('{closure Test::__invoke}', Callback::toString(Closure::fromCallable($test)));
	Assert::same('Test::__invoke', getName(Callback::toReflection($test)));
	Assert::same('Test::__invoke', getName(Callback::toReflection(Closure::fromCallable($test))));
	Assert::same('Test::__invoke*', Closure::fromCallable($test)->__invoke('*'));
});


test('object methods', function () {
	$test = new Test;
	Assert::same([$test, 'publicFun'], Callback::unwrap(Closure::fromCallable([$test, 'publicFun'])));

	Assert::same('Test::publicFun', Callback::toString([$test, 'publicFun']));
	Assert::same('{closure Test::publicFun}', Callback::toString(Closure::fromCallable([$test, 'publicFun'])));

	Assert::same('Test::publicFun', getName(Callback::toReflection([$test, 'publicFun'])));
	Assert::same('Test::publicFun', getName(Callback::toReflection(Closure::fromCallable([$test, 'publicFun']))));

	Assert::same('Test::publicFun*', Closure::fromCallable([$test, 'publicFun'])->__invoke('*'));


	Assert::same([$test, 'privateFun'], Callback::unwrap($test->createPrivateClosure()));

	Assert::same('Test::privateFun', Callback::toString([$test, 'privateFun']));
	Assert::same('{closure Test::privateFun}', Callback::toString($test->createPrivateClosure()));

	Assert::same('Test::privateFun', getName(Callback::toReflection([$test, 'privateFun'])));
	Assert::same('Test::privateFun', getName(Callback::toReflection($test->createPrivateClosure())));
	Assert::same('Test::privateFun', getName(Callback::toReflection((new TestChild)->createPrivateClosure())));

	Assert::same('Test::privateFun*', $test->createPrivateClosure()->__invoke('*'));

	Assert::same('Test::ref', Closure::fromCallable([$test, 'ref'])(...[&$res]));
	Assert::same('Test::ref', $res);
});


test('static methods', function () {
	$test = new Test;
	Assert::same(['Test', 'publicStatic'], Callback::unwrap(Closure::fromCallable(['Test', 'publicStatic'])));
	Assert::same(['Test', 'publicStatic'], Callback::unwrap(Closure::fromCallable('Test::publicStatic')));

	Assert::same('Test::publicStatic', Callback::toString(['Test', 'publicStatic']));
	Assert::same('Test::publicStatic', Callback::toString([$test, 'publicStatic']));
	Assert::same('Test::publicStatic', Callback::toString('Test::publicStatic'));
	Assert::same('{closure Test::publicStatic}', Callback::toString(Closure::fromCallable('Test::publicStatic')));

	Assert::same('Test::publicStatic', getName(Callback::toReflection(['Test', 'publicStatic'])));
	Assert::same('Test::publicStatic', getName(Callback::toReflection([$test, 'publicStatic'])));
	Assert::same('Test::publicStatic', getName(Callback::toReflection('Test::publicStatic')));
	Assert::same('Test::publicStatic', getName(Callback::toReflection(Closure::fromCallable('Test::publicStatic'))));

	Assert::same('Test::publicStatic*', Closure::fromCallable(['Test', 'publicStatic'])->__invoke('*'));
	Assert::same('Test::publicStatic*', Closure::fromCallable([$test, 'publicStatic'])->__invoke('*'));


	Assert::same(['Test', 'privateStatic'], Callback::unwrap(Test::createPrivateStaticClosure()));
	Assert::same('Test::privateStatic', Callback::toString('Test::privateStatic'));
	Assert::same('{closure Test::privateStatic}', Callback::toString(Test::createPrivateStaticClosure()));
	Assert::same('Test::privateStatic', getName(Callback::toReflection('Test::privateStatic')));
	Assert::same('Test::privateStatic', getName(Callback::toReflection(Test::createPrivateStaticClosure())));
	Assert::same('Test::privateStatic', getName(Callback::toReflection(TestChild::createPrivateStaticClosure())));

	Assert::same('Test::privateStatic*', Test::createPrivateStaticClosure()->__invoke('*'));
});


test('magic methods', function () {
	$test = new TestDynamic;
	Assert::same([$test, 'magic'], Callback::unwrap(Closure::fromCallable([$test, 'magic'])));
	Assert::same('TestDynamic::magic', Callback::toString([$test, 'magic']));
	Assert::same('{closure TestDynamic::magic}', Callback::toString(Closure::fromCallable([$test, 'magic'])));
	Assert::same('TestDynamic::__call magic *', Closure::fromCallable([$test, 'magic'])->__invoke('*'));

	Assert::same(['TestDynamic', 'magic'], Callback::unwrap(Closure::fromCallable('TestDynamic::magic')));
	Assert::same('TestDynamic::magic', Callback::toString('TestDynamic::magic'));
	Assert::same('{closure TestDynamic::magic}', Callback::toString(Closure::fromCallable('TestDynamic::magic')));
	Assert::same('TestDynamic::__callStatic magic *', Closure::fromCallable('TestDynamic::magic')->__invoke('*'));

	Assert::exception(
		fn() => Callback::toReflection([new TestDynamic, 'magic']),
		ReflectionException::class,
		'Method TestDynamic::magic() does not exist',
	);

	Assert::exception(
		fn() => Callback::toReflection(Closure::fromCallable([new TestDynamic, 'magic'])),
		ReflectionException::class,
		'Method TestDynamic::magic() does not exist',
	);
});


test('PHP bugs - is_callable($object, true) fails', function () {
	Assert::same('stdClass::__invoke', Callback::toString(new stdClass));

	Assert::exception(
		fn() => Callback::toReflection(new stdClass),
		ReflectionException::class,
		'Method stdClass::__invoke() does not exist',
	);
});
