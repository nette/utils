<?php

/**
 * Test: Nette\Utils\Type
 */

declare(strict_types=1);

use Nette\Utils\Type;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$type = Type::fromString('string');

Assert::same(['string'], $type->getNames());
Assert::equal([Type::fromString('string')], $type->getTypes());
Assert::same('string', (string) $type);
Assert::same('string', $type->getSingleName());
Assert::false($type->isClass());
Assert::false($type->isUnion());
Assert::true($type->isSingle());
Assert::true($type->isBuiltin());


$type = Type::fromString('string|null');

Assert::same(['string', 'null'], $type->getNames());
Assert::equal([Type::fromString('string'), Type::fromString('null')], $type->getTypes());
Assert::same('?string', (string) $type);
Assert::same('string', $type->getSingleName());
Assert::false($type->isClass());
Assert::true($type->isUnion());
Assert::true($type->isSingle());
Assert::true($type->isBuiltin());


$type = Type::fromString('null|string');

Assert::same(['string', 'null'], $type->getNames());
Assert::equal([Type::fromString('string'), Type::fromString('null')], $type->getTypes());
Assert::same('?string', (string) $type);
Assert::same('string', $type->getSingleName());
Assert::false($type->isClass());
Assert::true($type->isUnion());
Assert::true($type->isSingle());
Assert::true($type->isBuiltin());


$type = Type::fromString('?string');

Assert::same(['string', 'null'], $type->getNames());
Assert::equal([Type::fromString('string'), Type::fromString('null')], $type->getTypes());
Assert::same('?string', (string) $type);
Assert::same('string', $type->getSingleName());
Assert::false($type->isClass());
Assert::true($type->isUnion());
Assert::true($type->isSingle());
Assert::true($type->isBuiltin());


$type = Type::fromString('NS\Foo');

Assert::same(['NS\Foo'], $type->getNames());
Assert::equal([Type::fromString('NS\Foo')], $type->getTypes());
Assert::same('NS\Foo', (string) $type);
Assert::same('NS\Foo', $type->getSingleName());
Assert::true($type->isClass());
Assert::false($type->isUnion());
Assert::true($type->isSingle());
Assert::false($type->isBuiltin());


$type = Type::fromString('string|Foo');

Assert::same(['string', 'Foo'], $type->getNames());
Assert::equal([Type::fromString('string'), Type::fromString('Foo')], $type->getTypes());
Assert::same('string|Foo', (string) $type);
Assert::null($type->getSingleName());
Assert::false($type->isClass());
Assert::true($type->isUnion());
Assert::false($type->isSingle());
Assert::false($type->isBuiltin());


$type = Type::fromString('mixed');

Assert::same(['mixed'], $type->getNames());
Assert::equal([Type::fromString('mixed')], $type->getTypes());
Assert::same('mixed', (string) $type);
Assert::same('mixed', $type->getSingleName());
Assert::false($type->isClass());
Assert::false($type->isUnion());
Assert::true($type->isSingle());
Assert::true($type->isBuiltin());


$type = Type::fromString('null'); // invalid type

Assert::same(['null'], $type->getNames());
Assert::equal([Type::fromString('null')], $type->getTypes());
Assert::same('null', (string) $type);
Assert::same('null', $type->getSingleName());
Assert::false($type->isClass());
Assert::false($type->isUnion());
Assert::true($type->isSingle());
Assert::true($type->isBuiltin());
