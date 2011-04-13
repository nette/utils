<?php

/**
 * Test: Nette\StringUtils::checkEncoding()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::true( StringUtils::checkEncoding("\xc5\xbelu\xc5\xa5ou\xc4\x8dk\xc3\xbd"), 'UTF-8' ); // žluťoučký
Assert::true( StringUtils::checkEncoding("\x01"), 'C0' );
Assert::false( StringUtils::checkEncoding("\xed\xa0\x80"), 'surrogate pairs' ); // xD800
Assert::false( StringUtils::checkEncoding("\xef\xbb\xbf"), 'noncharacter' ); // xFEFF
Assert::false( StringUtils::checkEncoding("\xf4\x90\x80\x80"), 'out of range' ); // x110000
