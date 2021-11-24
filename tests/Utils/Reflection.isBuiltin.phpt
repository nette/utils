<?php

declare(strict_types=1);

use Nette\Utils\Reflection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::true(Reflection::isBuiltinType('int'));
Assert::true(Reflection::isBuiltinType('Int'));
Assert::false(Reflection::isBuiltinType('Foo'));

Assert::true(Reflection::isClassKeyword('self'));
Assert::true(Reflection::isClassKeyword('Self'));
Assert::true(Reflection::isClassKeyword('static'));
Assert::true(Reflection::isClassKeyword('parent'));
Assert::false(Reflection::isClassKeyword('Foo'));
