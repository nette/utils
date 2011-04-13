<?php

/**
 * Test: Nette\StringUtils::startsWith()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\StringUtils;



require __DIR__ . '/../bootstrap.php';



Assert::true( StringUtils::startsWith('123', NULL), "startsWith('123', NULL)" );
Assert::true( StringUtils::startsWith('123', ''), "startsWith('123', '')" );
Assert::true( StringUtils::startsWith('123', '1'), "startsWith('123', '1')" );
Assert::false( StringUtils::startsWith('123', '2'), "startsWith('123', '2')" );
Assert::true( StringUtils::startsWith('123', '123'), "startsWith('123', '123')" );
Assert::false( StringUtils::startsWith('123', '1234'), "startsWith('123', '1234')" );
