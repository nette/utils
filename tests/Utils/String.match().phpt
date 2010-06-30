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



require __DIR__ . '/../initialize.php';



T::dump( String::match('hello world!', '#([E-L])+#') );
T::dump( String::match('hello world!', '#([e-l])+#') );
T::dump( String::match('hello world!', '#[e-l]+#') );
T::dump( String::match('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );
T::dump( String::match('hello world!', '#[e-l]+#', NULL, 2) );



__halt_compiler();

------EXPECT------
NULL

array(
	"hell"
	"l"
)

array(
	"hell"
)

array(
	array(
		"hell"
		0
	)
)

array(
	"ll"
)
