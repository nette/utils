<?php

/**
 * Test: Nette\Utils\ObjectMixin::getSuggestion()
 */

use Nette\Utils\ObjectMixin;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(NULL, ObjectMixin::getSuggestion(array(), ''));
Assert::same(NULL, ObjectMixin::getSuggestion(array(), 'a'));
Assert::same(NULL, ObjectMixin::getSuggestion(array('a'), 'a'));
Assert::same('b', ObjectMixin::getSuggestion(array('a', 'b'), 'a'));
Assert::same('aa', ObjectMixin::getSuggestion(array('aa', 'bb'), 'a'));
Assert::same(NULL, ObjectMixin::getSuggestion(array('aaa', 'bbb'), 'a'));
Assert::same(NULL, ObjectMixin::getSuggestion(array('aaa', 'bbb'), 'ab'));
Assert::same(NULL, ObjectMixin::getSuggestion(array('aaa', 'bbb'), 'abc'));
Assert::same('bar', ObjectMixin::getSuggestion(array('foo', 'bar', 'baz'), 'baz'));
