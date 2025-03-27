<?php

declare(strict_types=1);

use Nette\Utils\Process;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// Different output types

test('output to files', function () {
	$tempFile = tempnam(sys_get_temp_dir(), 'process_test_');
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo "hello";'], stdout: $tempFile, stderr: false);
	$process->wait();
	Assert::same('hello', file_get_contents($tempFile));
	unlink($tempFile);
});

test('setting stderr to false prevents reading from getStdError() or consumeStdError()', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo fwrite(STDERR, "hello");'], stderr: false);
	$process->wait();
	Assert::exception(
		fn() => $process->getStdError(),
		LogicException::class,
		'Cannot read output: output capturing was not enabled',
	);
	Assert::exception(
		fn() => $process->consumeStdError(),
		LogicException::class,
		'Cannot read output: output capturing was not enabled',
	);
});

test('stream as output', function () {
	$tempFile = tempnam(sys_get_temp_dir(), 'process_test_');
	$output = fopen($tempFile, 'w');
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo "hello";'], stdout: $output);
	$process->wait();
	fclose($output);
	Assert::same('hello', file_get_contents($tempFile));
	unlink($tempFile);
});

test('stream as error output', function () {
	$tempFile = tempnam(sys_get_temp_dir(), 'process_test_');
	$output = fopen($tempFile, 'w');
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo fwrite(STDERR, "hello");'], stderr: $output);
	$process->wait();
	fclose($output);
	Assert::same('hello', file_get_contents($tempFile));
	unlink($tempFile);
});

test('changing both stdout and stderr does not trigger callbacks in wait()', function () {
	$tempFile = tempnam(sys_get_temp_dir(), 'process_test_');
	$output = fopen($tempFile, 'w');
	$wasCalled = false;
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo "hello";'], stdout: $output, stderr: $output);
	$process->wait(function () use (&$wasCalled) {
		$wasCalled = true;
	});
	fclose($output);
	Assert::false($wasCalled);
	unlink($tempFile);
});
