<?php

/**
 * Test: Nette\Utils\Strings and lower, upper, firstLower, firstUpper, capitalize
 * @phpExtension mbstring
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('ďábelské', Strings::lower('ĎÁBELSKÉ'));
Assert::same('ďÁBELSKÉ', Strings::firstLower('ĎÁBELSKÉ'));
Assert::same('ĎÁBELSKÉ', Strings::upper('ďábelské'));
Assert::same('Ďábelské', Strings::firstUpper('ďábelské'));
Assert::same('Ďábelské Ódy', Strings::capitalize('ďábelské ódy'));
