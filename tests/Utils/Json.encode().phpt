<?php

/**
 * Test: Nette\Utils\Json::encode()
 */

use Nette\Utils\Json;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('"ok"', Json::encode('ok'));


Assert::exception(function () {
	Json::encode(["bad utf\xFF"]);
}, Nette\Utils\JsonException::class, 'Malformed UTF-8 characters, possibly incorrectly encoded');


Assert::exception(function () {
	$arr = ['recursive'];
	$arr[] = & $arr;
	Json::encode($arr);
}, Nette\Utils\JsonException::class, '%a?%ecursion detected');


// default JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES
Assert::same("\"/I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n\"", Json::encode("/I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n"));
Assert::same('"\u2028\u2029"', Json::encode("\xe2\x80\xa8\xe2\x80\xa9"));

// JSON_PRETTY_PRINT
Assert::same("[\n    1,\n    2,\n    3\n]", Json::encode([1, 2, 3], Json::PRETTY));


Assert::exception(function () {
	Json::encode(NAN);
}, Nette\Utils\JsonException::class, 'Inf and NaN cannot be JSON encoded');


// JSON_PRESERVE_ZERO_FRACTION
Assert::same(PHP_VERSION_ID < 50606 ? '1' : '1.0', Json::encode(1.0));
