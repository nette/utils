<?php

/**
 * Test: Nette\Utils\ReflectionType
 */

declare(strict_types=1);

use Nette\Utils\ReflectionType;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$rt = new ReflectionType(['string']);

Assert::same(['string'], $rt->getTypes());
Assert::same('string', (string) $rt);
Assert::same('string', $rt->getSingleType());
Assert::false($rt->isUnion());
Assert::true($rt->isSingle());


$rt = new ReflectionType(['string', 'null']);

Assert::same(['string', 'null'], $rt->getTypes());
Assert::same('?string', (string) $rt);
Assert::same('string', $rt->getSingleType());
Assert::false($rt->isUnion());
Assert::true($rt->isSingle());


$rt = new ReflectionType(['string', 'Foo']);

Assert::same(['string', 'Foo'], $rt->getTypes());
Assert::same('string|Foo', (string) $rt);
Assert::null($rt->getSingleType());
Assert::true($rt->isUnion());
Assert::false($rt->isSingle());
