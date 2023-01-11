<?php

declare(strict_types=1);

namespace NS;

define('DEFINED', 123);
define('NS_DEFINED', 'xxx');
const NS_DEFINED = 456;

interface Bar
{
	const DEFINED = 'xyz';
}

class ParentFoo
{
	public const PUBLIC_DEFINED = 'xyz';
}

class Foo extends ParentFoo
{
	public const PUBLIC_DEFINED = 'abc';
	protected const PROTECTED_DEFINED = 'abc';
	private const PRIVATE_DEFINED = 'abc';


	public function method(
		$a,
		$b = self::PUBLIC_DEFINED,
		$c = Foo::PUBLIC_DEFINED,
		$d = SELF::PUBLIC_DEFINED,
		$e = Foo::PROTECTED_DEFINED,
		$f = self::PROTECTED_DEFINED,
		$g = Foo::PRIVATE_DEFINED,
		$h = self::PRIVATE_DEFINED,
		$i = self::UNDEFINED,
		$j = Foo::UNDEFINED,
		$k = bar::DEFINED,
		$l = Undefined::ANY,
		$m = DEFINED,
		$n = UNDEFINED,
		$o = NS_DEFINED,
		$p = parent::PUBLIC_DEFINED,
	) {
	}
}
