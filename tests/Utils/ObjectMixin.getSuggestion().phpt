<?php

/**
 * Test: Nette\Utils\ObjectMixin::getSuggestion()
 */

use Nette\Utils\ObjectMixin;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(null, ObjectMixin::getSuggestion([], ''));
Assert::same(null, ObjectMixin::getSuggestion([], 'a'));
Assert::same(null, ObjectMixin::getSuggestion(['a'], 'a'));
Assert::same('a', ObjectMixin::getSuggestion(['a', 'b'], ''));
Assert::same('b', ObjectMixin::getSuggestion(['a', 'b'], 'a')); // ignore 100% match
Assert::same('a1', ObjectMixin::getSuggestion(['a1', 'a2'], 'a')); // take first
Assert::same(null, ObjectMixin::getSuggestion(['aaa', 'bbb'], 'a'));
Assert::same(null, ObjectMixin::getSuggestion(['aaa', 'bbb'], 'ab'));
Assert::same(null, ObjectMixin::getSuggestion(['aaa', 'bbb'], 'abc'));
Assert::same('bar', ObjectMixin::getSuggestion(['foo', 'bar', 'baz'], 'baz'));
Assert::same('abcd', ObjectMixin::getSuggestion(['abcd'], 'acbd'));
Assert::same('abcd', ObjectMixin::getSuggestion(['abcd'], 'axbd'));
Assert::same(null, ObjectMixin::getSuggestion(['abcd'], 'axyd')); // 'tags' vs 'this'
Assert::same(null, ObjectMixin::getSuggestion(['setItem'], 'item'));
Assert::same('setItem', ObjectMixin::getSuggestion(['setItem'], 'Item'));
Assert::same('setItem', ObjectMixin::getSuggestion(['setItem'], 'addItem'));
Assert::same(null, ObjectMixin::getSuggestion(['addItem'], 'addItem'));


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
