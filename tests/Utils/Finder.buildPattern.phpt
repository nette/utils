<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Finder buildPattern.
 */

use Nette\Utils\Finder;
use Nette\Utils\Helpers;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::with(new Finder, function () {
	$i = Helpers::IsWindows ? 'i' : '';

	// lone * matches everything
	Assert::same('##', $this->buildPattern('*'));

	// plain mask floats at any segment boundary, ./ anchors to the search root
	Assert::same('#(?:^|/)abc$#D' . $i, $this->buildPattern('abc'));
	Assert::same('#^abc$#D' . $i, $this->buildPattern('./abc'));
	Assert::same('#(?:^|/)(.+/)?x$#D' . $i, $this->buildPattern('**/x'));

	// wildcard behavior
	$match = fn(string $mask, string $subject): bool => (bool) preg_match($this->buildPattern($mask), $subject);

	Assert::true($match('*.txt', 'a.txt'));
	Assert::true($match('*.txt', 'dir/a.txt')); // floats to any depth
	Assert::false($match('*.txt', 'a.txt.bak'));
	Assert::false($match('*.txt', 'a/b.txt.bak'));

	Assert::true($match('a?c', 'abc'));
	Assert::false($match('a?c', 'a/c')); // ? does not cross a slash
	Assert::false($match('a*c', 'a/c')); // neither does *

	Assert::true($match('./f*', 'file'));
	Assert::false($match('./f*', 'dir/file')); // anchored by ./

	Assert::true($match('**/x', 'x'));
	Assert::true($match('**/x', 'a/b/x'));

	// character classes and their negation
	Assert::true($match('[a-c]x', 'bx'));
	Assert::false($match('[a-c]x', 'dx'));
	Assert::true($match('[!a-c]x', 'dx'));
	Assert::false($match('[!a-c]x', 'bx'));

	// escaped brackets via [[] and []]
	Assert::true($match('[[]x[]]', '[x]'));

	// ** not forming a whole segment is just two stars
	Assert::true($match('a**b', 'ab'));
	Assert::true($match('a**b', 'axb'));
	Assert::false($match('a**b', 'a/b'));
});
