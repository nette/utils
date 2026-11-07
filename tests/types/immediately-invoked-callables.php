<?php declare(strict_types=1);

/**
 * PHPStan tests: exceptions thrown by callbacks that are invoked before the method returns
 * are attributed to the caller. Run by TypesTest.phpt with immediately-invoked-callables.neon.
 */

use Nette\Utils\{Arrays, Callback, Finder, Helpers, Iterables, Process, Strings};


function testArraysFirst(): void
{
	Arrays::first([1], fn() => throw new Exception);
	Arrays::first([1], null, fn() => throw new Exception);
}


function testArraysLast(): void
{
	Arrays::last([1], fn() => throw new Exception);
	Arrays::last([1], null, fn() => throw new Exception);
}


function testArraysFirstKey(): void
{
	Arrays::firstKey([1], fn() => throw new Exception);
}


function testArraysLastKey(): void
{
	Arrays::lastKey([1], fn() => throw new Exception);
}


function testArraysSome(): void
{
	Arrays::some([1], fn() => throw new Exception);
}


function testArraysEvery(): void
{
	Arrays::every([1], fn() => throw new Exception);
}


function testArraysFilter(): void
{
	Arrays::filter([1], fn() => throw new Exception);
}


function testArraysMap(): void
{
	Arrays::map([1], fn() => throw new Exception);
}


/** @param array<int, int> $array */
function testArraysMapWithKeys(array $array): void
{
	Arrays::mapWithKeys($array, function (int $v): array {
		if ($v < 0) {
			throw new Exception;
		}
		return [$v, $v];
	});
}


function testIterablesFirst(): void
{
	Iterables::first([1], fn() => throw new Exception);
	Iterables::first([1], null, fn() => throw new Exception);
}


function testIterablesFirstKey(): void
{
	Iterables::firstKey([1], fn() => throw new Exception);
	Iterables::firstKey([1], null, fn() => throw new Exception);
}


function testIterablesSome(): void
{
	Iterables::some([1], fn() => throw new Exception);
}


function testIterablesEvery(): void
{
	Iterables::every([1], fn() => throw new Exception);
}


function testStringsReplace(): void
{
	Strings::replace('a', '#a#', fn() => throw new Exception);
}


function testHelpersCapture(): void
{
	Helpers::capture(fn() => throw new Exception);
}


function testCallbackInvokeSafe(): void
{
	Callback::invokeSafe('strlen', ['a'], fn() => throw new Exception);
}


function testProcessWait(Process $process): void
{
	$process->wait(fn() => throw new Exception);
}


// lazy methods store the callback and invoke it later, so the exception is not thrown by the call
function testIterablesMapIsLazy(): void
{
	Iterables::map([1], fn() => throw new Exception);
}


function testFinderFilterIsLazy(): void
{
	Finder::findFiles()->filter(fn() => throw new Exception);
}
