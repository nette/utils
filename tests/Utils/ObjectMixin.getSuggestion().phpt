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
Assert::same('a', ObjectMixin::getSuggestion(['a', 'b'], ''));
Assert::same('b', ObjectMixin::getSuggestion(['a', 'b'], 'a')); // ignore 100% match
Assert::same('a1', ObjectMixin::getSuggestion(['a1', 'a2'], 'a')); // take first
Assert::same(NULL, ObjectMixin::getSuggestion(['aaa', 'bbb'], 'a'));
Assert::same(NULL, ObjectMixin::getSuggestion(['aaa', 'bbb'], 'ab'));
Assert::same(NULL, ObjectMixin::getSuggestion(['aaa', 'bbb'], 'abc'));
Assert::same('bar', ObjectMixin::getSuggestion(['foo', 'bar', 'baz'], 'baz'));
Assert::same('abcd', ObjectMixin::getSuggestion(['abcd'], 'acbd'));
Assert::same('abcd', ObjectMixin::getSuggestion(['abcd'], 'axbd'));
Assert::same(NULL, ObjectMixin::getSuggestion(['abcd'], 'axyd')); // 'tags' vs 'this'
Assert::same(NULL, ObjectMixin::getSuggestion(['setItem'], 'item'));
Assert::same('setItem', ObjectMixin::getSuggestion(['setItem'], 'Item'));
Assert::same('setItem', ObjectMixin::getSuggestion(['setItem'], 'addItem'));
Assert::same(NULL, ObjectMixin::getSuggestion(['addItem'], 'addItem'));


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
