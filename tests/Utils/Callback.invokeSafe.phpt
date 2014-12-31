<?php

/**
 * Test: Nette\Utils\Callback::invokeSafe()
 */

use Nette\Utils\Callback,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


set_error_handler(function($severity, $message) use (& $res) {
	$res = $message;
});


// no error
Callback::invokeSafe('trim', array(''), function() {});

trigger_error('OK1', E_USER_WARNING);
Assert::same('OK1', $res);


// skipped error
Callback::invokeSafe('trim', array(array()), function() {});
Assert::same('OK1', $res);


// ignored error
Callback::invokeSafe('trim', array(array()), function() {
	return FALSE;
});
Assert::same('trim() expects parameter 1 to be string, array given', $res);


// error -> exception
Assert::exception(function() {
	Callback::invokeSafe('trim', array(array()), function($message, $severity) {
		throw new Exception($message, $severity);
	});
}, 'Exception', 'trim() expects parameter 1 to be string, array given', E_WARNING);

trigger_error('OK2', E_USER_WARNING);
Assert::same('OK2', $res);


// error inside
Callback::invokeSafe('preg_replace_callback', array('#.#', function(){
	$a++;
}, 'x'), function() {
	throw new Exception('Should not be thrown');
});

Assert::same('Undefined variable: a', $res);


// exception inside
Assert::exception(function() {
	Callback::invokeSafe('preg_replace_callback', array('#.#', function(){
		throw new Exception('in callback');
	}, 'x'), function() {});
}, 'Exception', 'in callback');

trigger_error('OK3', E_USER_WARNING);
Assert::same('OK3', $res);
