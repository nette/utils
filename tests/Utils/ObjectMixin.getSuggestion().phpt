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
Assert::same('a', ObjectMixin::getSuggestion(array('a', 'b'), ''));
Assert::same('b', ObjectMixin::getSuggestion(array('a', 'b'), 'a')); // ignore 100% match
Assert::same('a1', ObjectMixin::getSuggestion(array('a1', 'a2'), 'a')); // take first
Assert::same(NULL, ObjectMixin::getSuggestion(array('aaa', 'bbb'), 'a'));
Assert::same(NULL, ObjectMixin::getSuggestion(array('aaa', 'bbb'), 'ab'));
Assert::same(NULL, ObjectMixin::getSuggestion(array('aaa', 'bbb'), 'abc'));
Assert::same('bar', ObjectMixin::getSuggestion(array('foo', 'bar', 'baz'), 'baz'));
Assert::same('abcd', ObjectMixin::getSuggestion(array('abcd'), 'acbd'));
Assert::same('abcd', ObjectMixin::getSuggestion(array('abcd'), 'axbd'));
Assert::same(NULL, ObjectMixin::getSuggestion(array('abcd'), 'axyd'));


/*
length  allowed ins/del  replacements
-------------------------------------
0       1                0
1       1                1
2       1                1
3       1                1
4       2                1
5       2                2
6       2                2
7       2                2
8       3                2
*/
