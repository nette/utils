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



require __DIR__ . '/../NetteTest/initialize.php';



dump( String::fixEncoding("\xc5\xbea\x01b\xed\xa0\x80c\xef\xbb\xbfd\xf4\x90\x80\x80e") ); // C0, surrogate pairs, noncharacter, out of range



__halt_compiler() ?>

------EXPECT------
string(8) "\xc5\xbea\x01bcde"
