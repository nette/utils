<?php

declare(strict_types=1);

use Nette\Utils\Helpers;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(null, Helpers::getSuggestion([], ''));
Assert::same(null, Helpers::getSuggestion([], 'a'));
Assert::same(null, Helpers::getSuggestion(['a'], 'a'));
Assert::same('a', Helpers::getSuggestion(['a', 'b'], ''));
Assert::same('b', Helpers::getSuggestion(['a', 'b'], 'a')); // ignore 100% match
Assert::same('a1', Helpers::getSuggestion(['a1', 'a2'], 'a')); // take first
Assert::same(null, Helpers::getSuggestion(['aaa', 'bbb'], 'a'));
Assert::same(null, Helpers::getSuggestion(['aaa', 'bbb'], 'ab'));
Assert::same(null, Helpers::getSuggestion(['aaa', 'bbb'], 'abc'));
Assert::same('bar', Helpers::getSuggestion(['foo', 'bar', 'baz'], 'baz'));
Assert::same('abcd', Helpers::getSuggestion(['abcd'], 'acbd'));
Assert::same('abcd', Helpers::getSuggestion(['abcd'], 'axbd'));
Assert::same(null, Helpers::getSuggestion(['abcd'], 'axyd')); // 'tags' vs 'this'
Assert::same(null, Helpers::getSuggestion(['setItem'], 'item'));
