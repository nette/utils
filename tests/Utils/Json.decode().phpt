<?php

/**
 * Test: Nette\Utils\Json::decode()
 */

use Nette\Utils\Json,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( "ok", Json::decode('"ok"') );
Assert::null( Json::decode('') );
Assert::null( Json::decode('null') );
Assert::null( Json::decode('NULL') );


Assert::equal( (object) ['a' => 1], Json::decode('{"a":1}') );
Assert::same( ['a' => 1], Json::decode('{"a":1}', Json::FORCE_ARRAY) );


Assert::exception(function() {
	Json::decode('{');
}, 'Nette\Utils\JsonException', 'Syntax error, malformed JSON');


Assert::exception(function() {
	Json::decode('{}}');
}, 'Nette\Utils\JsonException', 'Syntax error, malformed JSON');


Assert::exception(function() {
	Json::decode("\x00");
}, 'Nette\Utils\JsonException', defined('JSON_C_VERSION') ? 'Syntax error, malformed JSON' : 'Unexpected control character found');


Assert::exception(function() {
	Json::decode('{"\u0000": 1}');
}, 'Nette\Utils\JsonException', 'Unexpected control character found');


Assert::same( ["\x00" => 1], Json::decode('{"\u0000": 1}', Json::FORCE_ARRAY) );
Assert::equal( (object) ['a' => "\x00"], Json::decode('{"a": "\u0000"}') );
Assert::equal( (object) ["\"\x00" => 1], Json::decode('{"\"\u0000": 1}') );


Assert::exception(function() {
	Json::decode("\"\xC1\xBF\"");
}, 'Nette\Utils\JsonException', 'Invalid UTF-8 sequence');


// default JSON_BIGINT_AS_STRING
if (PHP_VERSION_ID >= 50400) {
	if (defined('JSON_C_VERSION')) {
		if (PHP_INT_SIZE > 4) {
			# 64-bit
			Assert::same( [9223372036854775807], Json::decode('[12345678901234567890]') );   # trimmed to max 64-bit integer
		} else {
			# 32-bit
			Assert::same( ['9223372036854775807'], Json::decode('[12345678901234567890]') );  # trimmed to max 64-bit integer
		}

	} else {
		Assert::same( ['12345678901234567890'], Json::decode('[12345678901234567890]') );
	}
}
