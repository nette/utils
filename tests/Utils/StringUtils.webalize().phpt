<?php

/**
 * Test: Nette\StringUtils::webalize()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( 'zlutoucky-kun-oooo', StringUtils::webalize("&\xc5\xbdLU\xc5\xa4OU\xc4\x8cK\xc3\x9d K\xc5\xae\xc5\x87 \xc3\xb6\xc5\x91\xc3\xb4o!") ); // &ŽLUŤOUČKÝ KŮŇ öőôo!
Assert::same( 'ZLUTOUCKY-KUN-oooo', StringUtils::webalize("&\xc5\xbdLU\xc5\xa4OU\xc4\x8cK\xc3\x9d K\xc5\xae\xc5\x87 \xc3\xb6\xc5\x91\xc3\xb4o!", NULL, FALSE) ); // &ŽLUŤOUČKÝ KŮŇ öőôo!
Assert::same( '1-4-!',  StringUtils::webalize("\xc2\xBC!", '!') );
