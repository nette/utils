<?php

/**
 * Test: Nette\Utils\Callback::invokeSafe()
 */

use Nette\Utils\Callback;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


set_error_handler(function ($severity, $message) use (& $res) {
	$res = $message;
});


// no error
Callback::invokeSafe('trim', [''], function () {});

trigger_error('OK1', E_USER_WARNING);
Assert::same('OK1', $res);


// skipped error
$counter = 0;
Callback::invokeSafe('preg_match', ['ab', 'foo'], function () use (& $counter) { $counter++; });
Assert::same('OK1', $res);
Assert::same(1, $counter);


// ignored error
$counter = 0;
Callback::invokeSafe('preg_match', ['ab', 'foo'], function () use (& $counter) {
	$counter++;
	return FALSE;
});
Assert::same('preg_match(): Delimiter must not be alphanumeric or backslash', $res);
Assert::same(1, $counter);


// error -> exception
Assert::exception(function () {
	Callback::invokeSafe('preg_match', ['ab', 'foo'], function ($message, $severity) {
		throw new Exception($message, $severity);
	});
}, 'Exception', 'Delimiter must not be alphanumeric or backslash', E_WARNING);

trigger_error('OK2', E_USER_WARNING);
Assert::same('OK2', $res);


// error inside
Callback::invokeSafe('preg_replace_callback', ['#.#', function () {
	$a++;
}, 'x'], function () {
	throw new Exception('Should not be thrown');
});

Assert::same('Undefined variable: a', $res);


// exception inside
Assert::exception(function () {
	Callback::invokeSafe('preg_replace_callback', ['#.#', function () {
		throw new Exception('in callback');
	}, 'x'], function () {});
}, 'Exception', 'in callback');

trigger_error('OK3', E_USER_WARNING);
Assert::same('OK3', $res);


// checking return value
$counter = 0;
Callback::invokeSafe('strpos', ['a', 'b'], function () use (& $counter) { $counter++; });
Assert::same(1, $counter);

$counter = 0;
Callback::invokeSafe('strpos', ['a', 'b'], function () use (& $counter) { $counter++; }, FALSE);
Assert::same(0, $counter);
