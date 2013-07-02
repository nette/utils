<?php

/**
 * Test: Nette\Utils\Arrays::searchKey()
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


Assert::same( 2, Arrays::searchKey($arr, '1') );
Assert::same( 2, Arrays::searchKey($arr, 1) );
Assert::same( 1, Arrays::searchKey($arr, 0) );
Assert::same( 0, Arrays::searchKey($arr, NULL) );
Assert::same( 0, Arrays::searchKey($arr, '') );
Assert::false( Arrays::searchKey($arr, 'undefined') );
