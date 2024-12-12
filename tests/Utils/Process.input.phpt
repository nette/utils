<?php declare(strict_types=1);

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

test('large string input', function () {
	$input = str_repeat('x', 200_000); // larger than a typical OS pipe buffer
	$process = Process::runExecutable(PHP_BINARY, ['-r', 'echo strlen(stream_get_contents(STDIN));'], stdin: $input);
	Assert::same('200000', $process->getStdOutput());
});

test('invalid input type is rejected before the process starts', function () {
	Assert::exception(
		fn() => Process::runExecutable(PHP_BINARY, ['-r', 'sleep(10);'], stdin: false),
		Nette\InvalidArgumentException::class,
		'Input must be string, resource, Process or null, bool given.',
	);
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
