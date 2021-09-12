<?php

declare(strict_types=1);

use Nette\Utils\Type;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$type = Type::fromReflection((new ReflectionObject(new class {
	public $foo;
}))->getProperty('foo'));
Assert::null($type);
