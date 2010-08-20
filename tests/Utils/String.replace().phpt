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

Assert::same( 'hello world!', String::replace('hello world!', '#([E-L])+#', '#') );
Assert::same( '#o wor#d!', String::replace('hello world!', '#([e-l])+#', '#') );
Assert::same( '@o wor@d!', String::replace('hello world!', '#[e-l]+#', callback('Test::cb')) );
Assert::same( '@o wor@d!', String::replace('hello world!', '#[e-l]+#', array('Test', 'cb')) );
Assert::same( '#@ @@@#d!', String::replace('hello world!', array(
	'#([e-l])+#' => '#',
	'#[o-w]#' => '@',
)) );
