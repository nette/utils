<?php

/**
 * Test: Nette\Utils\Json::decode()
 */

use Nette\Utils\Json;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

$hasJsonC = defined('JSON_C_VERSION'); // PHP <= 5.6 on Debian/Ubuntu


Assert::same('ok', Json::decode('"ok"'));
Assert::null(Json::decode('null'));
Assert::null(Json::decode(' null'));


Assert::equal((object) ['a' => 1], Json::decode('{"a":1}'));
Assert::same(['a' => 1], Json::decode('{"a":1}', Json::FORCE_ARRAY));


Assert::exception(function () {
	Json::decode('');
}, Nette\Utils\JsonException::class, 'Syntax error');


if ($hasJsonC) {
	Assert::null(Json::decode('NULL'));
} else {
	Assert::exception(function () {
		Json::decode('NULL');
	}, Nette\Utils\JsonException::class, 'Syntax error');
}


Assert::exception(function () {
	Json::decode('{');
}, Nette\Utils\JsonException::class, $hasJsonC ? 'unexpected end of data' : 'Syntax error');


Assert::exception(function () {
	Json::decode('{}}');
}, Nette\Utils\JsonException::class, PHP_VERSION_ID < 70000 ? ($hasJsonC ?  'unexpected character' : 'State mismatch (invalid or malformed JSON)') : 'Syntax error');


Assert::exception(function () {
	Json::decode("\x00");
}, Nette\Utils\JsonException::class, $hasJsonC ? 'unexpected end of data' : 'Control character error, possibly incorrectly encoded');


Assert::exception(function () {
	Json::decode('{"\u0000": 1}');
}, Nette\Utils\JsonException::class, 'The decoded property name is invalid');


if ($hasJsonC) {
	Assert::same(['' => 1], Json::decode('{"\u0000": 1}', Json::FORCE_ARRAY));
	Assert::equal((object) ['"' => 1], Json::decode('{"\"\u0000": 1}'));
} else {
	Assert::same(["\x00" => 1], Json::decode('{"\u0000": 1}', Json::FORCE_ARRAY));
	Assert::equal((object) ["\"\x00" => 1], Json::decode('{"\"\u0000": 1}'));
}
Assert::equal((object) ['a' => "\x00"], Json::decode('{"a": "\u0000"}'));


Assert::exception(function () {
	Json::decode("\"\xC1\xBF\"");
}, Nette\Utils\JsonException::class, $hasJsonC ? 'Invalid UTF-8 sequence' : 'Malformed UTF-8 characters, possibly incorrectly encoded');


// default JSON_BIGINT_AS_STRING
if ($hasJsonC) {
	if (PHP_INT_SIZE > 4) {
		# 64-bit
		Assert::same([9223372036854775807], Json::decode('[12345678901234567890]'));   # trimmed to max 64-bit integer
	} else {
		Assert::error(function () {
			Json::decode('[12345678901234567890]');
		}, E_NOTICE, 'json_decode(): integer overflow detected');

		# 32-bit
		Assert::same(['9223372036854775807'], @Json::decode('[12345678901234567890]'));  # trimmed to max 64-bit integer
	}

} else {
	Assert::same(['12345678901234567890'], Json::decode('[12345678901234567890]'));
}
