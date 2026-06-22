<?php declare(strict_types=1);

/**
 * Test: Nette\Utils\Finder splitRecursivePart.
 */

use Nette\Utils\Finder;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::with(new Finder, function () {
	// no ** wildcard: whole directory part goes to glob, no recursion
	Assert::same(['a/', '', false], $this->splitRecursivePart('a/'));
	Assert::same(['/', '', false], $this->splitRecursivePart('/'));
	Assert::same(['/', 'a', false], $this->splitRecursivePart('/a'));
	Assert::same(['/a/', '', false], $this->splitRecursivePart('/a/'));
	Assert::same(['a/', 'b', false], $this->splitRecursivePart('a/b'));

	// ** as a whole segment in the directory part triggers recursion
	Assert::same(['/a/', '', true], $this->splitRecursivePart('/a/**/'));
	Assert::same(['/a/', 'c/', true], $this->splitRecursivePart('/a/**/c/'));
	Assert::same(['/', 'c/', true], $this->splitRecursivePart('/**/c/'));
	Assert::same(['', 'c/', true], $this->splitRecursivePart('**/c/'));
	Assert::same(['', '', true], $this->splitRecursivePart('**/'));
	Assert::same(['a/', 'b', true], $this->splitRecursivePart('a/**/b'));
	Assert::same(['a/', 'b/c', true], $this->splitRecursivePart('a/**/b/c'));

	// only the first ** splits; later ones stay in the traversal part
	Assert::same(['a/', 'b/**/c', true], $this->splitRecursivePart('a/**/b/**/c'));

	// ** in the file name part (not followed by /) is not recognized
	Assert::same(['/a/', '**', false], $this->splitRecursivePart('/a/**'));

	// ** glued to other characters is not a segment
	Assert::same(['a**/', 'b', false], $this->splitRecursivePart('a**/b'));
});
