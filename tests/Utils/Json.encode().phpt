<?php

/**
 * Test: Nette\Utils\Json::encode()
 */

declare(strict_types=1);

use Nette\Utils\Json;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('"ok"', Json::encode('ok'));


Assert::exception(function () {
	Json::encode(["bad utf\xFF"]);
}, Nette\Utils\JsonException::class, 'Malformed UTF-8 characters, possibly incorrectly encoded');


Assert::exception(function () {
	$arr = ['recursive'];
	$arr[] = &$arr;
	Json::encode($arr);
}, Nette\Utils\JsonException::class, '%a?%ecursion detected');


// default JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES
Assert::same("\"/I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n\"", Json::encode("/I\u{F1}t\u{EB}rn\u{E2}ti\u{F4}n\u{E0}liz\u{E6}ti\u{F8}n"));
Assert::same('"\u2028\u2029"', Json::encode("\u{2028}\u{2029}"));

// JSON_PRETTY_PRINT
Assert::same("[\n    1,\n    2,\n    3\n]", Json::encode([1, 2, 3], Json::PRETTY));


Assert::exception(function () {
	Json::encode(NAN);
}, Nette\Utils\JsonException::class, 'Inf and NaN cannot be JSON encoded');


// JSON_PRESERVE_ZERO_FRACTION
Assert::same(defined('JSON_PRESERVE_ZERO_FRACTION') ? '1.0' : '1', Json::encode(1.0));
