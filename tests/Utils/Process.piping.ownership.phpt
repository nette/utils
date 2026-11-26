<?php declare(strict_types=1);

use Nette\Utils\{Helpers, Process};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

if (Helpers::IsWindows) {
	Tester\Environment::skip('Process piping is not supported on Windows.');
}


// After piping, the destination process alone owns the read end of the source's STDOUT. When it exits, the source
// must get a broken pipe instead of blocking on a pipe nobody reads.

$writer = fn(float $timeout = 10) => Process::runExecutable(
	getPhpCliBinary(),
	['-r', '$s = str_repeat("x", 65536); for ($i = 0; $i < 1600; $i++) { if (@fwrite(STDOUT, $s) === false) exit(3); }'], // 100 MiB
	timeout: $timeout,
);
$readFirst = fn(Process $source, int $bytes, mixed $stdout = false) => Process::runExecutable(
	getPhpCliBinary(),
	['-r', "echo strlen(fread(STDIN, $bytes));"],
	stdin: $source,
	stdout: $stdout,
	timeout: 10,
);


test('the source ends when the destination exits early', function () use ($writer, $readFirst) {
	$source = $writer();
	$destination = $readFirst($source, 1000);
	$start = hrtime(true);
	Assert::same(3, $source->getExitCode()); // write failed: broken pipe
	Assert::true((hrtime(true) - $start) / 1e9 < 5);
	Assert::same(0, $destination->getExitCode());
});


test('every stage of a pipeline ends when the last one exits early', function () use ($writer, $readFirst) {
	$a = $writer();
	$b = Process::runExecutable(
		getPhpCliBinary(),
		['-r', 'while (($s = fread(STDIN, 65536)) !== "" && $s !== false) { if (@fwrite(STDOUT, $s) === false) exit(4); }'],
		stdin: $a,
		timeout: 10,
	);
	$c = $readFirst($b, 1000);
	$start = hrtime(true);
	Assert::same(0, $c->getExitCode());
	Assert::same(4, $b->getExitCode());
	Assert::same(3, $a->getExitCode());
	Assert::true((hrtime(true) - $start) / 1e9 < 5);
});


test('the destination cannot be written to and closing its STDIN does nothing', function () use ($writer, $readFirst) {
	$source = $writer();
	$destination = $readFirst($source, 1000, null);
	Assert::exception(
		fn() => $destination->writeStdInput('x'),
		Nette\InvalidStateException::class,
		'Cannot write to process: STDIN pipe is closed',
	);
	$destination->closeStdInput();
	Assert::same('1000', $destination->getStdOutput());
	Assert::same(3, $source->getExitCode());
});


test('the source no longer captures its STDOUT', function () use ($writer, $readFirst) {
	$source = $writer();
	$readFirst($source, 1000);
	Assert::exception(
		fn() => $source->consumeStdOutput(),
		Nette\InvalidStateException::class,
		'Cannot read output: it is not captured %a%',
	);
});


test('if the destination cannot be created, the source keeps its output', function () {
	$source = Process::runExecutable(getPhpCliBinary(), ['-r', 'echo str_repeat("y", 1000);']);
	Assert::exception(
		fn() => Process::runExecutable(getPhpCliBinary(), ['-r', 'echo 1;'], stdin: $source, stdout: __DIR__ . '/nonexistent-dir/x.txt'),
		Nette\IOException::class,
	);
	Assert::same(str_repeat('y', 1000), $source->getStdOutput());
});
