<?php

/**
 * Test: Nette\String::replace()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



class Test
{
	static function cb() {
		return '@';
	}
}

T::dump( String::replace('hello world!', '#([E-L])+#', '#') );
T::dump( String::replace('hello world!', '#([e-l])+#', '#') );
T::dump( String::replace('hello world!', '#[e-l]+#', callback('Test::cb')) );
T::dump( String::replace('hello world!', '#[e-l]+#', array('Test', 'cb')) );
T::dump( String::replace('hello world!', array(
	'#([e-l])+#' => '#',
	'#[o-w]#' => '@',
)) );



__halt_compiler();

------EXPECT------
"hello world!"

"#o wor#d!"

"@o wor@d!"

"@o wor@d!"

"#@ @@@#d!"
