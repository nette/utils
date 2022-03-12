<?php

/**
 * Test: Expanding class alias to FQN.
 */

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


require __DIR__ . '/fixtures.reflection/expandClass.noNamespace.php';
require __DIR__ . '/fixtures.reflection/expandClass.inBracketedNamespace.php';
require __DIR__ . '/fixtures.reflection/expandClass.inNamespace.php';

if (PHP_VERSION_ID >= 80100) {
	require __DIR__ . '/fixtures.reflection/expandClass.special.php';
	Assert::same('A\B', Reflection::expandClassName('C', new ReflectionClass(T::class)));
	Assert::same('A\B', Reflection::expandClassName('C', new ReflectionClass(I::class)));
	Assert::same('A\B', Reflection::expandClassName('C', new ReflectionClass(E::class)));
}

$rcTest = new ReflectionClass(Test::class);
$rcBTest = new ReflectionClass(BTest::class);
$rcFoo = new ReflectionClass(Test\Space\Foo::class);
$rcBar = new ReflectionClass(Test\Space\Bar::class);


Assert::exception(
	fn() => Reflection::expandClassName('', $rcTest),
	Nette\InvalidArgumentException::class,
	'Class name must not be empty.',
);


Assert::exception(
	fn() => Reflection::expandClassName('A', new ReflectionClass(new class {
	})),
	Nette\NotImplementedException::class,
	'Anonymous classes are not supported.',
);


Assert::same('A', Reflection::expandClassName('A', $rcTest));
Assert::same('A\B', Reflection::expandClassName('C', $rcTest));

Assert::same('BTest', Reflection::expandClassName('BTest', $rcBTest));

Assert::same('Test\Space\Foo', Reflection::expandClassName('self', $rcFoo));
Assert::same('Test\Space\Foo', Reflection::expandClassName('Self', $rcFoo));

Assert::same('parent', Reflection::expandClassName('parent', $rcFoo));
Assert::same('Test\Space\Foo', Reflection::expandClassName('parent', new ReflectionClass(new class extends Test\Space\Foo {
})));

foreach (['String', 'string', 'int', 'float', 'bool', 'array', 'callable', 'iterable', 'void', 'null'] as $type) {
	Assert::same(strtolower($type), Reflection::expandClassName($type, $rcFoo));
}

/*
alias to expand => [
	FQN for $rcFoo,
	FQN for $rcBar
]
*/
$cases = [
	'\Absolute' => [
		'Absolute',
		'Absolute',
	],
	'\Absolute\Foo' => [
		'Absolute\Foo',
		'Absolute\Foo',
	],

	'AAA' => [
		'Test\Space\AAA',
		'AAA',
	],
	'AAA\Foo' => [
		'Test\Space\AAA\Foo',
		'AAA\Foo',
	],

	'B' => [
		'Test\Space\B',
		'BBB',
	],
	'B\Foo' => [
		'Test\Space\B\Foo',
		'BBB\Foo',
	],

	'DDD' => [
		'Test\Space\DDD',
		'CCC\DDD',
	],
	'DDD\Foo' => [
		'Test\Space\DDD\Foo',
		'CCC\DDD\Foo',
	],

	'F' => [
		'Test\Space\F',
		'EEE\FFF',
	],
	'F\Foo' => [
		'Test\Space\F\Foo',
		'EEE\FFF\Foo',
	],

	'HHH' => [
		'Test\Space\HHH',
		'Test\Space\HHH',
	],

	'Notdef' => [
		'Test\Space\Notdef',
		'Test\Space\Notdef',
	],
	'Notdef\Foo' => [
		'Test\Space\Notdef\Foo',
		'Test\Space\Notdef\Foo',
	],

	// trim leading backslash
	'G' => [
		'Test\Space\G',
		'GGG',
	],
	'G\Foo' => [
		'Test\Space\G\Foo',
		'GGG\Foo',
	],
];
foreach ($cases as $alias => $fqn) {
	Assert::same($fqn[0], Reflection::expandClassName($alias, $rcFoo));
	Assert::same($fqn[1], Reflection::expandClassName($alias, $rcBar));
}

Assert::same(
	['C' => 'A\B'],
	Reflection::getUseStatements(new ReflectionClass('Test')),
);

Assert::same(
	[],
	Reflection::getUseStatements(new ReflectionClass('Test\Space\Foo')),
);

Assert::same(
	['AAA' => 'AAA', 'B' => 'BBB', 'DDD' => 'CCC\DDD', 'F' => 'EEE\FFF', 'G' => 'GGG'],
	Reflection::getUseStatements(new ReflectionClass('Test\Space\Bar')),
);
Assert::same(
	[],
	Reflection::getUseStatements(new ReflectionClass('stdClass')),
);

Assert::exception(
	fn() => Reflection::getUseStatements(new ReflectionClass(new class {
	})),
	Nette\NotImplementedException::class,
	'Anonymous classes are not supported.',
);
