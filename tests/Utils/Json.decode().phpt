<?php

/**
 * Test: Nette\Json::decode()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Json;



require __DIR__ . '/../initialize.php';



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
	Assert::exception('Nette\JsonException', 'Syntax error, malformed JSON', $e );
}



try {
	Json::decode('{}}');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\JsonException', 'Syntax error, malformed JSON', $e );
}



try {
	Json::decode("\x00");
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\JsonException', 'Unexpected control character found', $e );
}
