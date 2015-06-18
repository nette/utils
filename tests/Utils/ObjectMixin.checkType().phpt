<?php

/**
 * Test: Nette\Utils\ObjectMixin::checkType()
 */

use Nette\Utils\ObjectMixin;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class StrClass
{
	function __toString()
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

assertAccepts('', [NULL, TRUE, FALSE, 0, 0.0, 0.1, 1, 12, '', 'true', 'false', '-123', '123x', '+1.2', '1.0', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);
assertAccepts('mixed', [NULL, TRUE, FALSE, 0, 0.0, 0.1, 1, 12, '', 'true', 'false', '-123', '123x', '+1.2', '1.0', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);

assertAccepts('scalar', [TRUE, FALSE, 0, 0.0, 0.1, 1, 12, '', 'true', 'false', '-123', '123x', '+1.2', '1.0']);
assertRejects('scalar', [NULL, [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);

assertAccepts('boolean', [TRUE, FALSE]);
assertAccepts('bool', [TRUE, FALSE]);
assertRejects('bool', [[], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);
assertConverts('bool', [
	[NULL, FALSE],
	[0, FALSE],
	[0.0, FALSE],
	[0.1, TRUE],
	[1, TRUE],
	[12, TRUE],
	['', FALSE],
	['0', FALSE],
	['false', TRUE],
	['-123', TRUE],
	['123x', TRUE],
]);

assertAccepts('string', ['', 'true', 'false', '-123', '123x', '+1.2', '1.0']);
assertRejects('string', [[], [1, 2], ['a', 'b'], new stdClass, $resource]);
assertConverts('string', [
	[NULL, ''],
	[TRUE, '1'],
	[FALSE, ''],
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
	[NULL, 0],
	[TRUE, 1],
	[FALSE, 0],
	[0.0, 0],
	['-123', -123],
	['1.0', 1],
]);

assertAccepts('float', [0.0, 0.1]);
assertRejects('float', ['', 'true', 'false', '123x', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);
assertConverts('float', [
	[NULL, 0.0],
	[TRUE, 1.0],
	[FALSE, 0.0],
	[0, 0.0],
	[1, 1.0],
	[12, 12.0],
	['-123', -123.0],
	['+1.2', 1.2],
	['1.0', 1.0],
]);

assertAccepts('array', [[], [1, 2], ['a', 'b']]);
assertRejects('array', [NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', new stdClass, new StrClass, $resource]);

assertAccepts('object', [new stdClass, new StrClass]);
assertRejects('object', [NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', [], [1, 2], ['a', 'b'], $resource]);

assertAccepts('callable', [[new StrClass, '__toString']]);
assertRejects('callable', [NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);

assertAccepts('resource', [$resource]);
assertRejects('resource', [NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', [], [1, 2], ['a', 'b'], new stdClass, new StrClass]);

assertAccepts('stdClass', [new stdClass]);
assertRejects('stdClass', [NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', [], [1, 2], ['a', 'b'], new StrClass, $resource]);

assertAccepts('null', [NULL]);
assertAccepts('NULL', [NULL]);
assertRejects('NULL', [TRUE, FALSE, 0, 0.1, 12, '', '123x', [], [1, 2], ['a', 'b'], new stdClass, new StrClass, $resource]);

assertAccepts('int[]', [[], [1, 2]]);
assertRejects('int[]', [NULL, TRUE, FALSE, 0, 0.1, 12, '', '123x', ['a', 'b'], new stdClass, new StrClass, $resource]);
assertConverts('int[]', [
	[['1'], [1]],
]);

$val = ['1', new stdClass];
ObjectMixin::checkType($val, 'int[]');
Assert::equal(['1', new stdClass], $val); // do not modify

assertAccepts('array|string', ['', '123x', [], [1, 2], ['a', 'b']]);
assertRejects('array|string', [new stdClass, $resource]);
assertConverts('array|string', [
	[NULL, ''],
	[TRUE, '1'],
	[FALSE, ''],
	[0, '0'],
	[0.0, '0'],
	[0.1, '0.1'],
	[1, '1'],
	[12, '12'],
	[new StrClass, '1'],
]);


assertAccepts('string|bool|NULL', [NULL, TRUE, FALSE, '', '123x']);
assertRejects('string|bool|NULL', [[], [1, 2], ['a', 'b'], new stdClass, $resource]);
assertConverts('string|bool|NULL', [
	[0, '0'],
	[0.0, '0'],
	[0.1, '0.1'],
	[1, '1'],
	[12, '12'],
	[new StrClass, '1'],
]);
