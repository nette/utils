<?php

/**
 * Test: Nette\StringUtils::replace()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



class Test
{
	static function cb() {
		return '@';
	}
}

Assert::same( 'hello world!', StringUtils::replace('hello world!', '#([E-L])+#', '#') );
Assert::same( '#o wor#d!', StringUtils::replace('hello world!', '#([e-l])+#', '#') );
Assert::same( '@o wor@d!', StringUtils::replace('hello world!', '#[e-l]+#', callback('Test::cb')) );
Assert::same( '@o wor@d!', StringUtils::replace('hello world!', '#[e-l]+#', array('Test', 'cb')) );
Assert::same( '#@ @@@#d!', StringUtils::replace('hello world!', array(
	'#([e-l])+#' => '#',
	'#[o-w]#' => '@',
)) );
