<?php

/**
 * Test: Nette\Utils\Json::decode()
 */

declare(strict_types=1);

use Nette\Utils\Json;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('ok', Json::decode('"ok"'));
Assert::null(Json::decode('null'));
Assert::null(Json::decode(' null'));


Assert::equal((object) ['a' => 1], Json::decode('{"a":1}'));
Assert::same(['a' => 1], Json::decode('{"a":1}', Json::FORCE_ARRAY));


Assert::exception(
	fn() => Json::decode(''),
	Nette\Utils\JsonException::class,
	'Syntax error',
);


Assert::exception(
	fn() => Json::decode('NULL'),
	Nette\Utils\JsonException::class,
	'Syntax error',
);


Assert::exception(
	fn() => Json::decode('{'),
	Nette\Utils\JsonException::class,
	'Syntax error',
);


Assert::exception(
	fn() => Json::decode('{}}'),
	Nette\Utils\JsonException::class,
	'Syntax error',
);


Assert::exception(
	fn() => Json::decode("\x00"),
	Nette\Utils\JsonException::class,
	defined('JSON_C_VERSION') ? 'Syntax error' : 'Control character error, possibly incorrectly encoded',
);


Assert::exception(
	fn() => Json::decode('{"\u0000": 1}'),
	Nette\Utils\JsonException::class,
	'The decoded property name is invalid',
);


Assert::same(["\x00" => 1], Json::decode('{"\u0000": 1}', Json::FORCE_ARRAY));
Assert::equal((object) ['a' => "\x00"], Json::decode('{"a": "\u0000"}'));
Assert::equal((object) ["\"\x00" => 1], Json::decode('{"\"\u0000": 1}'));


Assert::exception(
	fn() => Json::decode("\"\xC1\xBF\""),
	Nette\Utils\JsonException::class,
	'Malformed UTF-8 characters, possibly incorrectly encoded',
);


// default JSON_BIGINT_AS_STRING
if (defined('JSON_C_VERSION')) {
	if (PHP_INT_SIZE > 4) {
		// 64-bit
		Assert::same([9_223_372_036_854_775_807], Json::decode('[12345678901234567890]'));   // trimmed to max 64-bit integer
	} else {
		// 32-bit
		Assert::same(['9223372036854775807'], Json::decode('[12345678901234567890]'));  // trimmed to max 64-bit integer
	}

} else {
	Assert::same(['12345678901234567890'], Json::decode('[12345678901234567890]'));
}


// JSON_* constants support
Assert::same('ab', Json::decode("\"a\x80b\"", JSON_INVALID_UTF8_IGNORE));
