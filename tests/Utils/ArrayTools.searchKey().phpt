<?php

/**
 * Test: Nette\ArrayTools::searchKey()
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


Assert::same( 2, ArrayTools::searchKey($arr, '1') );
Assert::same( 2, ArrayTools::searchKey($arr, 1) );
Assert::same( 1, ArrayTools::searchKey($arr, 0) );
Assert::same( 0, ArrayTools::searchKey($arr, NULL) );
Assert::same( 0, ArrayTools::searchKey($arr, '') );
Assert::false( ArrayTools::searchKey($arr, 'undefined') );
