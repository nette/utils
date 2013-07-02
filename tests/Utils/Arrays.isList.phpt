<?php

/**
 * Test: Nette\Utils\Arrays::isList()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Arrays;


require __DIR__ . '/../bootstrap.php';


Assert::false( Arrays::isList(NULL) );
Assert::true( Arrays::isList(array()) );
Assert::true( Arrays::isList(array(1)) );
Assert::true( Arrays::isList(array('a', 'b', 'c')) );
Assert::false( Arrays::isList(array(4 => 1, 2, 3)) );
Assert::false( Arrays::isList(array(1 => 'a', 0 => 'b')) );
Assert::false( Arrays::isList(array('key' => 'value')) );
$arr = array();
$arr[] = & $arr;
Assert::true( Arrays::isList($arr) );
