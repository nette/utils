<?php

/**
 * Test: Nette\ArrayTools::searchKey()
 *
 * @author     David Grudl
 * @category   Nette
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

T::dump( $arr );

T::dump( ArrayTools::searchKey($arr, '1') );
T::dump( ArrayTools::searchKey($arr, 1) );
T::dump( ArrayTools::searchKey($arr, 0) );
T::dump( ArrayTools::searchKey($arr, NULL) );
T::dump( ArrayTools::searchKey($arr, '') );
T::dump( ArrayTools::searchKey($arr, 'undefined') );



__halt_compiler() ?>

------EXPECT------
array(
	"" => "first"
	0 => "second"
	1 => "third"
	7 => "fourth"
)

2

2

1

0

0

FALSE
