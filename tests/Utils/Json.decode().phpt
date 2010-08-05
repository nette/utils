<?php

/**
 * Test: Nette\Json::decode()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Json;



require __DIR__ . '/../initialize.php';



T::dump( Json::decode('"ok"') );

T::dump( Json::decode('') );
T::dump( Json::decode('null') );
T::dump( Json::decode('NULL') );

T::dump( Json::decode('{"a":1}') );
T::dump( Json::decode('{"a":1}', Json::FORCE_ARRAY) );


try {
	T::dump( Json::decode('{') );
} catch (Exception $e) {
	T::dump( $e );
}



try {
	T::dump( Json::decode('{}}') );
} catch (Exception $e) {
	T::dump( $e );
}



try {
	T::dump( Json::decode("\x00") );
} catch (Exception $e) {
	T::dump( $e );
}



__halt_compiler() ?>

------EXPECT------
"ok"

NULL

NULL

NULL

stdClass(
	"a" => 1
)

array(
	"a" => 1
)

Exception %ns%JsonException: #4 Syntax error, malformed JSON

Exception %ns%JsonException: #2 Syntax error, malformed JSON

Exception %ns%JsonException: #3 Unexpected control character found
