<?php

/**
 * Test: Nette\String::replace()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 * @phpversion 5.3
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



T::dump( String::replace('hello world!', '#[e-l]+#', function() { return '@'; }) );



__halt_compiler();

------EXPECT------
"@o wor@d!"
