<?php

/**
 * Test: Nette\ArrayTools::searchKey()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\ArrayTools;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$arr  = array(
	NULL => 'first',
	FALSE => 'second',
	1 => 'third',
	7 => 'fourth'
);

dump( $arr );

dump( ArrayTools::searchKey($arr, '1') );
dump( ArrayTools::searchKey($arr, 1) );
dump( ArrayTools::searchKey($arr, 0) );
dump( ArrayTools::searchKey($arr, NULL) );
dump( ArrayTools::searchKey($arr, '') );
dump( ArrayTools::searchKey($arr, 'undefined') );



__halt_compiler() ?>

------EXPECT------
array(4) {
	"" => string(5) "first"
	0 => string(6) "second"
	1 => string(5) "third"
	7 => string(6) "fourth"
}

int(2)

int(2)

int(1)

int(0)

int(0)

bool(FALSE)
