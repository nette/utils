<?php

/**
 * Test: Nette\String::fixEncoding()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\String;



require __DIR__ . '/../initialize.php';



T::dump( String::fixEncoding("\xc5\xbea\x01b\xed\xa0\x80c\xef\xbb\xbfd\xf4\x90\x80\x80e") ); // C0, surrogate pairs, noncharacter, out of range



__halt_compiler() ?>

------EXPECT------
"\xc5\xbea\x01bcde"
