<?php

/**
 * Test: Nette\Utils\ReflectionType
 */

declare(strict_types=1);

use Nette\Utils\ReflectionType;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class Foo
{
}

class FooChild extends Foo
{
}


$rt = new ReflectionType(['string']);

Assert::same(['string'], $rt->getTypes());
Assert::same('string', (string) $rt);
Assert::same('string', $rt->getSingleType());
Assert::false($rt->isUnion());
Assert::false($rt->isIntersection());
Assert::true($rt->isSingle());
Assert::true($rt->allows('string'));
Assert::false($rt->allows('null'));
Assert::false($rt->allows('Foo'));
Assert::false($rt->allows('FooChild'));


$rt = new ReflectionType(['string', 'null']);

Assert::same(['string', 'null'], $rt->getTypes());
Assert::same('?string', (string) $rt);
Assert::same('string', $rt->getSingleType());
Assert::false($rt->isUnion());
Assert::false($rt->isIntersection());
Assert::true($rt->isSingle());
Assert::true($rt->allows('string'));
Assert::true($rt->allows('null'));
Assert::false($rt->allows('Foo'));
Assert::false($rt->allows('FooChild'));


$rt = new ReflectionType(['string', 'Foo']);

Assert::same(['string', 'Foo'], $rt->getTypes());
Assert::same('string|Foo', (string) $rt);
Assert::null($rt->getSingleType());
Assert::true($rt->isUnion());
Assert::false($rt->isIntersection());
Assert::false($rt->isSingle());
Assert::true($rt->allows('string'));
Assert::false($rt->allows('null'));
Assert::true($rt->allows('Foo'));
Assert::true($rt->allows('FooChild'));


$rt = new ReflectionType(['Bar', 'Foo'], true);

Assert::same(['Bar', 'Foo'], $rt->getTypes());
Assert::same('Bar&Foo', (string) $rt);
Assert::null($rt->getSingleType());
Assert::false($rt->isUnion());
Assert::true($rt->isIntersection());
Assert::false($rt->isSingle());
Assert::false($rt->allows('string'));
Assert::false($rt->allows('null'));
Assert::false($rt->allows('Foo'));
Assert::false($rt->allows('FooChild'));


$rt = new ReflectionType(['mixed']);

Assert::same(['mixed'], $rt->getTypes());
Assert::same('mixed', (string) $rt);
Assert::same('mixed', $rt->getSingleType());
Assert::false($rt->isUnion());
Assert::false($rt->isIntersection());
Assert::true($rt->isSingle());
Assert::true($rt->allows('string'));
Assert::true($rt->allows('null'));
Assert::true($rt->allows('Foo'));
Assert::true($rt->allows('FooChild'));
