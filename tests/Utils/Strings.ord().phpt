<?php

/**
 * Test: Nette\Utils\Strings::ord()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(0x000000, Strings::ord("\x00"));
Assert::same(0x00007F, Strings::ord("\x7F"));
Assert::same(0x000080, Strings::ord("\u{80}"));
Assert::same(0x0007FF, Strings::ord("\u{7FF}"));
Assert::same(0x000800, Strings::ord("\u{800}"));
Assert::same(0x00D7FF, Strings::ord("\u{D7FF}"));
Assert::same(0x00E000, Strings::ord("\u{E000}"));
Assert::same(0x00FFFF, Strings::ord("\u{FFFF}"));
Assert::same(0x010000, Strings::ord("\u{10000}"));
Assert::same(0x10FFFF, Strings::ord("\u{10FFFF}"));
Assert::same(0x000080, Strings::ord("\u{80}\u{80}"));

Assert::exception(
	fn() => Strings::ord("\u{D800}"),
	Nette\InvalidArgumentException::class,
	'Invalid UTF-8 character "\xEDA080".',
);

Assert::exception(
	fn() => Strings::ord(''),
	Nette\InvalidArgumentException::class,
	'Invalid UTF-8 character "".',
);

Assert::exception(
	fn() => Strings::ord("\xFF"),
	Nette\InvalidArgumentException::class,
	'Invalid UTF-8 character "\xFF".',
);
