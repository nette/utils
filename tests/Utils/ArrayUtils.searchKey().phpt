<?php

/**
 * Test: Nette\ArrayUtils::searchKey()
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


Assert::same( 2, ArrayUtils::searchKey($arr, '1') );
Assert::same( 2, ArrayUtils::searchKey($arr, 1) );
Assert::same( 1, ArrayUtils::searchKey($arr, 0) );
Assert::same( 0, ArrayUtils::searchKey($arr, NULL) );
Assert::same( 0, ArrayUtils::searchKey($arr, '') );
Assert::false( ArrayUtils::searchKey($arr, 'undefined') );
