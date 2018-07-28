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

class Foo
{
	const DEFINED = 'abc';


	public function method(
		$a,
		$b = self::DEFINED,
		$c = Foo::DEFINED,
		$d = SELF::DEFINED,
		$e = bar::DEFINED,
		$f = self::UNDEFINED,
		$g = Undefined::ANY,
		$h = DEFINED,
		$i = UNDEFINED,
		$j = NS_DEFINED
	) {
	}
}
