<?php

/**
 * Test: Nette\Utils\Arrays::grep() errors.
 *
 * @author     David Grudl
 * @package    Nette\Utils
 * @subpackage UnitTests
 */

use Nette\Utils\Arrays;



require __DIR__ . '/../bootstrap.php';



try {
	Arrays::grep(array('a', '1', 'c'), '#*#');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'preg_grep(): Compilation failed: nothing to repeat at offset 0 in pattern: #*#', $e );
}


try {
	Arrays::grep(array('a', "1\xFF", 'c'), '#\d#u');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('Nette\Utils\RegexpException', 'Malformed UTF-8 data (pattern: #\d#u)', $e );
}
