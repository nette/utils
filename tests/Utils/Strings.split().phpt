<?php

/**
 * Test: Nette\Utils\Strings::split()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


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
