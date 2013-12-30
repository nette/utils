<?php

/**
 * Test: Nette\Utils\Strings::split()
 *
 * @author     David Grudl
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same( array(
	'a',
	',',
	'b',
	',',
	'c',
), Strings::split('a, b, c', '#(,)\s*#') );

Assert::same( array(
	'a',
	',',
	'b',
	',',
	'c',
), Strings::split('a, b, c', '#(,)\s*#', PREG_SPLIT_NO_EMPTY) );
