<?php

/**
 * Test: Nette\String::matchAll()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../NetteTest/initialize.php';



dump( String::matchAll('hello world!', '#([E-L])+#') );
dump( String::matchAll('hello world!', '#([e-l])+#') );
dump( String::matchAll('hello world!', '#[e-l]+#') );
dump( String::matchAll('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );
dump( String::matchAll('hello world!', '#[e-l]+#', PREG_PATTERN_ORDER, 2) );



__halt_compiler();

------EXPECT------
array(0)

array(2) {
	0 => array(2) {
		0 => string(4) "hell"
		1 => string(1) "l"
	}
	1 => array(2) {
		0 => string(1) "l"
		1 => string(1) "l"
	}
}

array(2) {
	0 => array(1) {
		0 => string(4) "hell"
	}
	1 => array(1) {
		0 => string(1) "l"
	}
}

array(2) {
	0 => array(1) {
		0 => array(2) {
			0 => string(4) "hell"
			1 => int(0)
		}
	}
	1 => array(1) {
		0 => array(2) {
			0 => string(1) "l"
			1 => int(9)
		}
	}
}

array(1) {
	0 => array(2) {
		0 => string(2) "ll"
		1 => string(1) "l"
	}
}
