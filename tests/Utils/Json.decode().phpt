<?php

/**
 * Test: Nette\Utils\Json::decode()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 * @subpackage UnitTests
 */

use Nette\Utils\Json;



require __DIR__ . '/../bootstrap.php';



Assert::same( "ok", Json::decode('"ok"') );
Assert::null( Json::decode('') );
Assert::null( Json::decode('null') );
Assert::null( Json::decode('NULL') );


Assert::equal( (object) array('a' => 1), Json::decode('{"a":1}') );
Assert::same( array('a' => 1), Json::decode('{"a":1}', Json::FORCE_ARRAY) );



try {
	Json::decode('{');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\JsonException', 'Syntax error, malformed JSON', $e );
}



try {
	Json::decode('{}}');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\JsonException', 'Syntax error, malformed JSON', $e );
}



try {
	Json::decode("\x00");
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\JsonException', 'Unexpected control character found', $e );
}
