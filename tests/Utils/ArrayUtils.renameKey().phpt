<?php

/**
 * Test: Nette\ArrayUtils::renameKey()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayUtils;



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


ArrayUtils::renameKey($arr, '1', 'new1');
ArrayUtils::renameKey($arr, 0, 'new2');
ArrayUtils::renameKey($arr, NULL, 'new3');
ArrayUtils::renameKey($arr, '', 'new4');
ArrayUtils::renameKey($arr, 'undefined', 'new5');

Assert::same( array(
	'new3' => 'first',
	'new2' => 'second',
	'new1' => 'third',
	7 => 'fourth',
), $arr );
