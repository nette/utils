<?php

/**
 * Test: Nette\Utils\Arrays::toObject()
 */

declare(strict_types=1);

use Nette\Utils\Arrays;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test('converts an empty array to the provided object without properties', function () {
	$obj = new stdClass;
	$res = Arrays::toObject([], $obj);
	Assert::same($res, $obj);
	Assert::type(stdClass::class, $res);
	Assert::same([], (array) $res);
});

test('converts an associative array to object properties', function () {
	$obj = new stdClass;
	$res = Arrays::toObject(['a' => 1, 'b' => 2], $obj);
	Assert::same($res, $obj);
	Assert::type(stdClass::class, $res);
	Assert::same(['a' => 1, 'b' => 2], (array) $res);
});

test('converts Traversable input to object properties', function () {
	$obj = new stdClass;
	$res = Arrays::toObject(new ArrayIterator(['a' => 1, 'b' => 2]), $obj);
	Assert::same($res, $obj);
	Assert::type(stdClass::class, $res);
	Assert::same(['a' => 1, 'b' => 2], (array) $res);
});
