<?php

/**
 * Test: Nette\ArrayTools::grep()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayTools;



require __DIR__ . '/../initialize.php';



Assert::same( array(
	1 => '1',
), ArrayTools::grep(array('a', '1', 'c'), '#\d#') );

Assert::same( array(
	0 => 'a',
	2 => 'c',
), ArrayTools::grep(array('a', '1', 'c'), '#\d#', PREG_GREP_INVERT) );
