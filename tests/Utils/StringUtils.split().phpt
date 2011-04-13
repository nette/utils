<?php

/**
 * Test: Nette\StringUtils::split()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( array(
	'a',
	',',
	'b',
	',',
	'c',
), StringUtils::split('a, b, c', '#(,)\s*#') );

Assert::same( array(
	'a',
	',',
	'b',
	',',
	'c',
), StringUtils::split('a, b, c', '#(,)\s*#', PREG_SPLIT_NO_EMPTY) );
