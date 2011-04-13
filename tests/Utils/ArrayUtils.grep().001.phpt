<?php

/**
 * Test: Nette\ArrayUtils::grep()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( array(
	1 => '1',
), ArrayUtils::grep(array('a', '1', 'c'), '#\d#') );

Assert::same( array(
	0 => 'a',
	2 => 'c',
), ArrayUtils::grep(array('a', '1', 'c'), '#\d#', PREG_GREP_INVERT) );
