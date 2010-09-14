<?php

/**
 * Test: Nette\ArrayTools::renameKey()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayTools;



require __DIR__ . '/../initialize.php';



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


ArrayTools::renameKey($arr, '1', 'new1');
ArrayTools::renameKey($arr, 0, 'new2');
ArrayTools::renameKey($arr, NULL, 'new3');
ArrayTools::renameKey($arr, '', 'new4');
ArrayTools::renameKey($arr, 'undefined', 'new5');

Assert::same( array(
	'new3' => 'first',
	'new2' => 'second',
	'new1' => 'third',
	7 => 'fourth',
), $arr );
