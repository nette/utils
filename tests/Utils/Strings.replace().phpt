<?php

/**
 * Test: Nette\Utils\Strings::replace()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Test
{
	static function cb() {
		return '@';
	}
}

Assert::same( 'hello world!', Strings::replace('hello world!', '#([E-L])+#', '#') );
Assert::same( '#o wor#d!', Strings::replace('hello world!', ['#([e-l])+#'], '#') );
Assert::same( '#o wor#d!', Strings::replace('hello world!', '#([e-l])+#', '#') );
Assert::same( '@o wor@d!', Strings::replace('hello world!', '#[e-l]+#', Nette\Utils\Callback::closure('Test::cb')) );
Assert::same( '@o wor@d!', Strings::replace('hello world!', ['#[e-l]+#'], Nette\Utils\Callback::closure('Test::cb')) );
Assert::same( '@o wor@d!', Strings::replace('hello world!', '#[e-l]+#', ['Test', 'cb']) );
Assert::same( '#@ @@@#d!', Strings::replace('hello world!', [
	'#([e-l])+#' => '#',
	'#[o-w]#' => '@',
]) );
