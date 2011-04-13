<?php

/**
 * Test: Nette\ArrayUtils::grep() errors.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\ArrayUtils;



require __DIR__ . '/../bootstrap.php';



try {
	ArrayUtils::grep(array('a', '1', 'c'), '#*#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'preg_grep(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}


try {
	ArrayUtils::grep(array('a', "1\xFF", 'c'), '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}
