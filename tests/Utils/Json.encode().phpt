<?php

/**
 * Test: Nette\Json::encode()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Json;



require __DIR__ . '/../initialize.php';



T::dump( Json::encode('ok') );



try {
	T::dump( Json::encode(array("bad utf\xFF")) );
} catch (Exception $e) {
	T::dump( $e );
}



try {
	$arr = array('recursive');
	$arr[] = & $arr;
	T::dump( Json::encode($arr) );
} catch (Exception $e) {
	T::dump( $e );
}



__halt_compiler() ?>

------EXPECT------
""ok""

Exception %ns%JsonException: json_encode(): Invalid UTF-8 sequence in argument

Exception %ns%JsonException: json_encode(): recursion detected
