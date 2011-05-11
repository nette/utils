<?php

/**
 * Test: Nette\Utils\Strings::fixEncoding()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 * @subpackage UnitTests
 */

use Nette\Utils\Strings;



require __DIR__ . '/../bootstrap.php';



Assert::same( "\xc5\xbea\x01bcde", Strings::fixEncoding("\xc5\xbea\x01b\xed\xa0\x80c\xef\xbb\xbfd\xf4\x90\x80\x80e") );
