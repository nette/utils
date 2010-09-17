<?php

/**
 * Test: Nette\Json::encode()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Json;



require __DIR__ . '/../bootstrap.php';



Assert::same( '"ok"', Json::encode('ok') );




try {
	Json::encode(array("bad utf\xFF"));
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\JsonException', 'json_encode(): Invalid UTF-8 sequence in argument', $e );
}



try {
	$arr = array('recursive');
	$arr[] = & $arr;
	Json::encode($arr);
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\JsonException', 'json_encode(): recursion detected', $e );
}
