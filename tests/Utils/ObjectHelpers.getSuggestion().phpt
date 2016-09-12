<?php

/**
 * Test: Nette\Utils\ObjectHelpers::getSuggestion()
 */

declare(strict_types=1);

use Nette\Utils\ObjectHelpers;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(NULL, ObjectHelpers::getSuggestion([], ''));
Assert::same(NULL, ObjectHelpers::getSuggestion([], 'a'));
Assert::same(NULL, ObjectHelpers::getSuggestion(['a'], 'a'));
Assert::same('a', ObjectHelpers::getSuggestion(['a', 'b'], ''));
Assert::same('b', ObjectHelpers::getSuggestion(['a', 'b'], 'a')); // ignore 100% match
Assert::same('a1', ObjectHelpers::getSuggestion(['a1', 'a2'], 'a')); // take first
Assert::same(NULL, ObjectHelpers::getSuggestion(['aaa', 'bbb'], 'a'));
Assert::same(NULL, ObjectHelpers::getSuggestion(['aaa', 'bbb'], 'ab'));
Assert::same(NULL, ObjectHelpers::getSuggestion(['aaa', 'bbb'], 'abc'));
Assert::same('bar', ObjectHelpers::getSuggestion(['foo', 'bar', 'baz'], 'baz'));
Assert::same('abcd', ObjectHelpers::getSuggestion(['abcd'], 'acbd'));
Assert::same('abcd', ObjectHelpers::getSuggestion(['abcd'], 'axbd'));
Assert::same(NULL, ObjectHelpers::getSuggestion(['abcd'], 'axyd')); // 'tags' vs 'this'
Assert::same(NULL, ObjectHelpers::getSuggestion(['setItem'], 'item'));
Assert::same('setItem', ObjectHelpers::getSuggestion(['setItem'], 'Item'));
Assert::same('setItem', ObjectHelpers::getSuggestion(['setItem'], 'addItem'));
Assert::same(NULL, ObjectHelpers::getSuggestion(['addItem'], 'addItem'));


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
