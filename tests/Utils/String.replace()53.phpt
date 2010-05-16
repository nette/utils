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



require __DIR__ . '/../NetteTest/initialize.php';



dump( String::replace('hello world!', '#[e-l]+#', function() { return '@'; }) );



__halt_compiler();

------EXPECT------
string(9) "@o wor@d!"
