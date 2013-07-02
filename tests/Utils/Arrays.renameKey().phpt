<?php

/**
 * Test: Nette\Utils\Arrays::renameKey()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Arrays;


require __DIR__ . '/../bootstrap.php';


$arr  = array(
	NULL => 'first',
	FALSE => 'second',
	1 => 'third',
	7 => 'fourth'
);

Assert::same( array(
	'' => 'first',
	0 => 'second',
	1 => 'third',
	7 => 'fourth',
), $arr );


Arrays::renameKey($arr, '1', 'new1');
Arrays::renameKey($arr, 0, 'new2');
Arrays::renameKey($arr, NULL, 'new3');
Arrays::renameKey($arr, '', 'new4');
Arrays::renameKey($arr, 'undefined', 'new5');

Assert::same( array(
	'new3' => 'first',
	'new2' => 'second',
	'new1' => 'third',
	7 => 'fourth',
), $arr );
