<?php

/**
 * Test: Nette\Utils\Type
 */

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::true(Validators::isTypeDeclaration('string'));
Assert::true(Validators::isTypeDeclaration('?string'));
Assert::true(Validators::isTypeDeclaration('string|null'));
Assert::true(Validators::isTypeDeclaration('Bar€'));
Assert::true(Validators::isTypeDeclaration('\Bar€'));
Assert::true(Validators::isTypeDeclaration('Bar€\Baz'));
Assert::true(Validators::isTypeDeclaration('\Bar€\Baz'));
Assert::true(Validators::isTypeDeclaration('A&B&C'));
Assert::true(Validators::isTypeDeclaration('(A&C)|(C&D)|true'));

Assert::false(Validators::isTypeDeclaration('?string|null'));
Assert::false(Validators::isTypeDeclaration('?'));
Assert::false(Validators::isTypeDeclaration(''));
Assert::false(Validators::isTypeDeclaration('Bar*'));
Assert::false(Validators::isTypeDeclaration('1a'));
Assert::false(Validators::isTypeDeclaration('\\\X'));
Assert::false(Validators::isTypeDeclaration('X\\'));
Assert::false(Validators::isTypeDeclaration('A\\\X'));
Assert::false(Validators::isTypeDeclaration('|foo'));
Assert::false(Validators::isTypeDeclaration('(A|B)'));
Assert::false(Validators::isTypeDeclaration('(A&B)'));
Assert::false(Validators::isTypeDeclaration('A&B|C'));
