<?php declare(strict_types=1);

use Nette\Utils\Helpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same(['Nette\Utils', 'Helpers'], Helpers::splitClassName(Helpers::class));
Assert::same(['', 'Class'], Helpers::splitClassName('Class'));
Assert::same(['', 'Class'], Helpers::splitClassName('\Class'));
Assert::same(['\A', 'B'], Helpers::splitClassName('\A\B'));
