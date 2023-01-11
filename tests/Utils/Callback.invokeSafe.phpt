<?php

/**
 * Test: Nette\Utils\Callback::invokeSafe()
 */

declare(strict_types=1);

use Nette\Utils\Callback;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


set_error_handler(function ($severity, $message) use (&$res) {
	$res = $message;
});


// no error
Callback::invokeSafe('trim', [''], function () {});

trigger_error('OK1', E_USER_WARNING);
Assert::same('OK1', $res);


// skipped error
Callback::invokeSafe('preg_match', ['ab', 'foo'], function () {});
Assert::same('OK1', $res);


// ignored error
Callback::invokeSafe('preg_match', ['ab', 'foo'], fn() => false);
Assert::match('preg_match(): Delimiter must not be alphanumeric%a%', $res);


// error -> exception
Assert::exception(function () {
	Callback::invokeSafe('preg_match', ['ab', 'foo'], function ($message, $severity) {
		throw new Exception($message, $severity);
	});
}, 'Exception', 'Delimiter must not be alphanumeric%a%', E_WARNING);

trigger_error('OK2', E_USER_WARNING);
Assert::same('OK2', $res);


// error inside
Callback::invokeSafe('preg_replace_callback', ['#.#', function () {
	$a++;
}, 'x'], function () {
	throw new Exception('Should not be thrown');
});

Assert::same(PHP_VERSION_ID < 80000 ? 'Undefined variable: a' : 'Undefined variable $a', $res);


// exception inside
Assert::exception(function () {
	Callback::invokeSafe('preg_replace_callback', ['#.#', function () {
		throw new Exception('in callback');
	}, 'x'], function () {});
}, 'Exception', 'in callback');

trigger_error('OK3', E_USER_WARNING);
Assert::same('OK3', $res);
