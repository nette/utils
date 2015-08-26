<?php

/**
 * Test: Nette\Utils\ObjectMixin::getSuggestion()
 */

use Nette\Utils\ObjectMixin;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(NULL, ObjectMixin::getSuggestion([], ''));
Assert::same(NULL, ObjectMixin::getSuggestion([], 'a'));
Assert::same(NULL, ObjectMixin::getSuggestion(['a'], 'a'));
Assert::same('b', ObjectMixin::getSuggestion(['a', 'b'], 'a'));
Assert::same('aa', ObjectMixin::getSuggestion(['aa', 'bb'], 'a'));
Assert::same(NULL, ObjectMixin::getSuggestion(['aaa', 'bbb'], 'a'));
Assert::same(NULL, ObjectMixin::getSuggestion(['aaa', 'bbb'], 'ab'));
Assert::same(NULL, ObjectMixin::getSuggestion(['aaa', 'bbb'], 'abc'));
Assert::same('bar', ObjectMixin::getSuggestion(['foo', 'bar', 'baz'], 'baz'));
