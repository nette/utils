<?php

/**
 * Test: Nette\Utils\Arrays::grep()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Arrays;


require __DIR__ . '/../bootstrap.php';


Assert::same( array(
	1 => '1',
), Arrays::grep(array('a', '1', 'c'), '#\d#') );

Assert::same( array(
	0 => 'a',
	2 => 'c',
), Arrays::grep(array('a', '1', 'c'), '#\d#', PREG_GREP_INVERT) );
