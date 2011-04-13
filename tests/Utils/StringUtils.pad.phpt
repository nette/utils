<?php

/**
 * Test: Nette\StringUtils::padLeft() & padRight()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::same( "ŤOUŤOUŤŽLU", StringUtils::padLeft("\xc5\xbdLU", 10, "\xc5\xa4OU") );
Assert::same( "ŤOUŤOUŽLU", StringUtils::padLeft("\xc5\xbdLU", 9, "\xc5\xa4OU") );
Assert::same( "ŽLU", StringUtils::padLeft("\xc5\xbdLU", 3, "\xc5\xa4OU") );
Assert::same( "ŽLU", StringUtils::padLeft("\xc5\xbdLU", 0, "\xc5\xa4OU") );
Assert::same( "ŽLU", StringUtils::padLeft("\xc5\xbdLU", -1, "\xc5\xa4OU") );
Assert::same( "ŤŤŤŤŤŤŤŽLU", StringUtils::padLeft("\xc5\xbdLU", 10, "\xc5\xa4") );
Assert::same( "ŽLU", StringUtils::padLeft("\xc5\xbdLU", 3, "\xc5\xa4") );
Assert::same( "       ŽLU", StringUtils::padLeft("\xc5\xbdLU", 10) );



Assert::same( "ŽLUŤOUŤOUŤ", StringUtils::padRight("\xc5\xbdLU", 10, "\xc5\xa4OU") );
Assert::same( "ŽLUŤOUŤOU", StringUtils::padRight("\xc5\xbdLU", 9, "\xc5\xa4OU") );
Assert::same( "ŽLU", StringUtils::padRight("\xc5\xbdLU", 3, "\xc5\xa4OU") );
Assert::same( "ŽLU", StringUtils::padRight("\xc5\xbdLU", 0, "\xc5\xa4OU") );
Assert::same( "ŽLU", StringUtils::padRight("\xc5\xbdLU", -1, "\xc5\xa4OU") );
Assert::same( "ŽLUŤŤŤŤŤŤŤ", StringUtils::padRight("\xc5\xbdLU", 10, "\xc5\xa4") );
Assert::same( "ŽLU", StringUtils::padRight("\xc5\xbdLU", 3, "\xc5\xa4") );
Assert::same( "ŽLU       ", StringUtils::padRight("\xc5\xbdLU", 10) );
