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



require __DIR__ . '/../initialize.php';



T::dump( String::matchAll('hello world!', '#([E-L])+#') );
T::dump( String::matchAll('hello world!', '#([e-l])+#') );
T::dump( String::matchAll('hello world!', '#[e-l]+#') );
T::dump( String::matchAll('hello world!', '#[e-l]+#', PREG_OFFSET_CAPTURE) );
T::dump( String::matchAll('hello world!', '#[e-l]+#', PREG_PATTERN_ORDER, 2) );



__halt_compiler();

------EXPECT------
array()

array(
	array(
		"hell"
		"l"
	)
	array(
		"l"
		"l"
	)
)

array(
	array(
		"hell"
	)
	array(
		"l"
	)
)

array(
	array(
		array(
			"hell"
			0
		)
	)
	array(
		array(
			"l"
			9
		)
	)
)

array(
	array(
		"ll"
		"l"
	)
)
