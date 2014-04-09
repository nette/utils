<?php

/**
 * Test: Nette\Utils\Json::encode()
 *
 * @author     David Grudl
 */

use Nette\Utils\Json,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( '"ok"', Json::encode('ok') );


// invalid UTF-8
Assert::exception(function() {
	Json::encode(array("bad utf\xFF"));
}, 'Nette\Utils\JsonException', '%a?%Invalid UTF-8 sequence%a?%');


// PHP bug #54058
if (PHP_VERSION_ID >= 50306) {
	Assert::exception(function() {
		Json::encode(Json::encode(array("bad utf\xFF", "good utf")));
	}, 'Nette\Utils\JsonException', '%a?%Invalid UTF-8 sequence%a?%');
}


if (PHP_VERSION_ID >= 50400) {
	// default JSON_UNESCAPED_UNICODE
	Assert::same( "\"I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n\"", Json::encode("I\xc3\xb1t\xc3\xabrn\xc3\xa2ti\xc3\xb4n\xc3\xa0liz\xc3\xa6ti\xc3\xb8n") );
	Assert::same( '"\u2028\u2029"', Json::encode("\xe2\x80\xa8\xe2\x80\xa9") );

	// JSON_PRETTY_PRINT
	Assert::same( "[\n    1,\n    2,\n    3\n]", Json::encode(array(1,2,3,), Json::PRETTY) );
}


// NAN
Assert::exception(function() {
	Json::encode(NAN);
}, 'Nette\Utils\JsonException', PHP_VERSION_ID >= 50500 ? 'Inf and NaN cannot be JSON encoded' : '%a% double NAN does not conform to the JSON spec, encoded as 0');


// INF
Assert::exception(function() {
	Json::encode(INF);
}, 'Nette\Utils\JsonException', PHP_VERSION_ID >= 50500 ? 'Inf and NaN cannot be JSON encoded' : '%a% double INF does not conform to the JSON spec, encoded as 0');


// resource
Assert::exception(function () {
	Json::encode(stream_context_create());
}, 'Nette\Utils\JsonException', PHP_VERSION_ID >= 50500 ? 'Type is not supported' : '%a% type is unsupported, encoded as null');


// recursion
Assert::exception(function() {
	$arr = array('recursive');
	$arr[] = & $arr;
	Json::encode($arr);
}, 'Nette\Utils\JsonException', PHP_VERSION_ID >= 50500 ? 'Recursion detected' : '%a% recursion detected');
