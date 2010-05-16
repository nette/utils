<?php

/**
 * Test: Nette\String::match()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../NetteTest/initialize.php';



dump( String::match('hello world!', '#([E-L])+#') );
dump( String::match('hello world!', '#([e-l])+#') );
dump( String::match('hello world!', '#[e-l]+#') );
dump( String::match('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );
dump( String::match('hello world!', '#[e-l]+#', NULL, 2) );



__halt_compiler();

------EXPECT------
NULL

array(2) {
	0 => string(4) "hell"
	1 => string(1) "l"
}

array(1) {
	0 => string(4) "hell"
}

array(1) {
	0 => array(2) {
		0 => string(4) "hell"
		1 => int(0)
	}
}

array(1) {
	0 => string(2) "ll"
}
