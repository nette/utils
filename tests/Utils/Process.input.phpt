<?php

declare(strict_types=1);

use Nette\Utils\Process;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// Different input types

test('string as input', function () {
	$input = 'Hello Input';
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo fgets(STDIN);'], stdin: $input);
	Assert::same('Hello Input', $process->getStdOutput());
});

test('stream as input', function () {
	$input = fopen('php://memory', 'r+');
	fwrite($input, 'Hello Input');
	rewind($input);
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo fgets(STDIN);'], stdin: $input);
	Assert::same('Hello Input', $process->getStdOutput());
});


// Writing input

test('write input', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo fgets(STDIN);'], stdin: null);
	$process->writeStdInput('hello' . PHP_EOL);
	$process->writeStdInput('world' . PHP_EOL);
	$process->closeStdInput();
	Assert::same('hello' . PHP_EOL, $process->getStdOutput());
});

test('writeStdInput() after closeStdInput() throws exception', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo fgets(STDIN);'], stdin: null);
	$process->writeStdInput('hello' . PHP_EOL);
	$process->closeStdInput();
	Assert::exception(
		fn() => $process->writeStdInput('world' . PHP_EOL),
		Nette\InvalidStateException::class,
		'Cannot write to process: STDIN pipe is closed',
	);
});

test('writeStdInput() throws exception when stdin is not null', function () {
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo fgets(STDIN);']);
	Assert::exception(
		fn() => $process->writeStdInput('hello' . PHP_EOL),
		Nette\InvalidStateException::class,
		'Cannot write to process: STDIN pipe is closed',
	);
});
