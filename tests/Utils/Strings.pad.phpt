<?php

/**
 * Test: Nette\Utils\Strings::padLeft() & padRight()
 *
 * @author     David Grudl
 * @package    Nette\Utils
 */

use Nette\Utils\Strings;


require __DIR__ . '/../bootstrap.php';


Assert::same( "ŤOUŤOUŤŽLU", Strings::padLeft("\xc5\xbdLU", 10, "\xc5\xa4OU") );
Assert::same( "ŤOUŤOUŽLU", Strings::padLeft("\xc5\xbdLU", 9, "\xc5\xa4OU") );
Assert::same( "ŽLU", Strings::padLeft("\xc5\xbdLU", 3, "\xc5\xa4OU") );
Assert::same( "ŽLU", Strings::padLeft("\xc5\xbdLU", 0, "\xc5\xa4OU") );
Assert::same( "ŽLU", Strings::padLeft("\xc5\xbdLU", -1, "\xc5\xa4OU") );
Assert::same( "ŤŤŤŤŤŤŤŽLU", Strings::padLeft("\xc5\xbdLU", 10, "\xc5\xa4") );
Assert::same( "ŽLU", Strings::padLeft("\xc5\xbdLU", 3, "\xc5\xa4") );
Assert::same( "       ŽLU", Strings::padLeft("\xc5\xbdLU", 10) );


Assert::same( "ŽLUŤOUŤOUŤ", Strings::padRight("\xc5\xbdLU", 10, "\xc5\xa4OU") );
Assert::same( "ŽLUŤOUŤOU", Strings::padRight("\xc5\xbdLU", 9, "\xc5\xa4OU") );
Assert::same( "ŽLU", Strings::padRight("\xc5\xbdLU", 3, "\xc5\xa4OU") );
Assert::same( "ŽLU", Strings::padRight("\xc5\xbdLU", 0, "\xc5\xa4OU") );
Assert::same( "ŽLU", Strings::padRight("\xc5\xbdLU", -1, "\xc5\xa4OU") );
Assert::same( "ŽLUŤŤŤŤŤŤŤ", Strings::padRight("\xc5\xbdLU", 10, "\xc5\xa4") );
Assert::same( "ŽLU", Strings::padRight("\xc5\xbdLU", 3, "\xc5\xa4") );
Assert::same( "ŽLU       ", Strings::padRight("\xc5\xbdLU", 10) );
