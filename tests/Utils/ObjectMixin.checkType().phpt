<?php

/**
 * Test: Nette\Utils\ObjectMixin::checkType()
 */

declare(strict_types=1);

use Nette\Utils\ObjectMixin;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class StrClass
{
	public function __toString()
	{
		return '1';
	}
}


function assertAccepts($type, $vals)
{
	foreach ($vals as $key => $val) {
		Assert::true(ObjectMixin::checkType($val, $type));
		Assert::same($vals[$key], $val);
	}
}


function assertRejects($type, $vals)
{
	foreach ($vals as $key => $val) {
		Assert::false(ObjectMixin::checkType($val, $type));
		Assert::same($vals[$key], $val);
	}
}


function assertConverts($type, $vals)
{
	foreach ($vals as $val) {
		Assert::true(ObjectMixin::checkType($val[0], $type));
		Assert::same($val[1], $val[0]);
	}
}


$resource = fopen(__FILE__, 'r');

assertAccepts('', [null, true, false, 0, 0.0, 0.1, 1, 12, '', 'true', 'false', '-123', '123x', '+1.2', '1.0', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);
assertAccepts('mixed', [null, true, false, 0, 0.0, 0.1, 1, 12, '', 'true', 'false', '-123', '123x', '+1.2', '1.0', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);

assertAccepts('scalar', [true, false, 0, 0.0, 0.1, 1, 12, '', 'true', 'false', '-123', '123x', '+1.2', '1.0']);
assertRejects('scalar', [null, [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);

assertAccepts('boolean', [true, false]);
assertAccepts('bool', [true, false]);
assertRejects('bool', [[], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);
assertConverts('bool', [
	[null, false],
	[0, false],
	[0.0, false],
	[0.1, true],
	[1, true],
	[12, true],
	['', false],
	['0', false],
	['false', true],
	['-123', true],
	['123x', true],
]);

assertAccepts('string', ['', 'true', 'false', '-123', '123x', '+1.2', '1.0']);
assertRejects('string', [[], [1, 2], ['a', 'b'], new stdClass, $resource]);
assertConverts('string', [
	[null, ''],
	[true, '1'],
	[false, ''],
	[0, '0'],
	[0.0, '0'],
	[0.1, '0.1'],
	[1, '1'],
	[12, '12'],
	[new StrClass, '1'],
]);

assertAccepts('integer', [0, 1, 12]);
assertAccepts('int', [0, 1, 12]);
assertRejects('int', [0.1, '', 'true', 'false', '123x', '+1.2', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);
assertConverts('int', [
	[null, 0],
	[true, 1],
	[false, 0],
	[0.0, 0],
	['-123', -123],
	['1.0', 1],
]);

assertAccepts('float', [0.0, 0.1]);
assertRejects('float', ['', 'true', 'false', '123x', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);
assertConverts('float', [
	[null, 0.0],
	[true, 1.0],
	[false, 0.0],
	[0, 0.0],
	[1, 1.0],
	[12, 12.0],
	['-123', -123.0],
	['+1.2', 1.2],
	['1.0', 1.0],
]);

assertAccepts('array', [[], [1, 2], ['a', 'b']]);
assertRejects('array', [null, true, false, 0, 0.1, 12, '', '123x', new stdClass, new StrClass, $resource]);

assertAccepts('object', [new stdClass, new StrClass]);
assertRejects('object', [null, true, false, 0, 0.1, 12, '', '123x', [], [1, 2], ['a', 'b'], $resource]);

assertAccepts('callable', [[new StrClass, '__toString']]);
assertRejects('callable', [null, true, false, 0, 0.1, 12, '', '123x', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);

assertAccepts('resource', [$resource]);
assertRejects('resource', [null, true, false, 0, 0.1, 12, '', '123x', [], [1, 2], ['a', 'b'], new stdClass, new StrClass]);

assertAccepts('stdClass', [new stdClass]);
assertRejects('stdClass', [null, true, false, 0, 0.1, 12, '', '123x', [], [1, 2], ['a', 'b'], new StrClass, $resource]);

assertAccepts('null', [null]);
assertAccepts('NULL', [null]);
assertRejects('NULL', [true, false, 0, 0.1, 12, '', '123x', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);

assertAccepts('int[]', [[], [1, 2]]);
assertRejects('int[]', [null, true, false, 0, 0.1, 12, '', '123x', ['a', 'b'], new stdClass, new StrClass, $resource]);
assertConverts('int[]', [
	[['1'], [1]],
]);

$val = ['1', new stdClass];
ObjectMixin::checkType($val, 'int[]');
Assert::equal(['1', new stdClass], $val); // do not modify

assertAccepts('array|string', ['', '123x', [], [1, 2], ['a', 'b']]);
assertRejects('array|string', [new stdClass, $resource]);
assertConverts('array|string', [
	[null, ''],
	[true, '1'],
	[false, ''],
	[0, '0'],
	[0.0, '0'],
	[0.1, '0.1'],
	[1, '1'],
	[12, '12'],
	[new StrClass, '1'],
]);


assertAccepts('string|bool|null', [null, true, false, '', '123x']);
assertRejects('string|bool|null', [[], [1, 2], ['a', 'b'], new stdClass, $resource]);
assertConverts('string|bool|null', [
	[0, '0'],
	[0.0, '0'],
	[0.1, '0.1'],
	[1, '1'],
	[12, '12'],
	[new StrClass, '1'],
]);
