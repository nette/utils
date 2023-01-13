<?php

declare(strict_types=1);

use Nette\Utils\Validators;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::true(Validators::isBuiltinType('int'));
Assert::true(Validators::isBuiltinType('Int'));
Assert::false(Validators::isBuiltinType('Foo'));

Assert::true(Validators::isClassKeyword('self'));
Assert::true(Validators::isClassKeyword('Self'));
Assert::true(Validators::isClassKeyword('static'));
Assert::true(Validators::isClassKeyword('parent'));
Assert::false(Validators::isClassKeyword('Foo'));
