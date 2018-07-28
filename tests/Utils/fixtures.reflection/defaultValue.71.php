<?php

declare(strict_types=1);

namespace NS;


class Foo
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
		$j = Foo::UNDEFINED
	) {
	}
}
