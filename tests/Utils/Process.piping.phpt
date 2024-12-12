<?php declare(strict_types=1);

use Nette\Utils\Helpers;
use Nette\Utils\Process;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


if (Helpers::IsWindows) {
	test('piping is not supported on Windows', function () {
		Assert::exception(
			fn() => Process::runExecutable(PHP_BINARY, ['-r', 'exit;'], stdin: Process::runExecutable(PHP_BINARY, ['-r', 'exit;'])),
			Nette\NotSupportedException::class,
			'Process piping is not supported on Windows.',
		);
	});
	return;
}


test('piping STDOUT of one process into another', function () {
	$process1 = Process::runExecutable(PHP_BINARY, ['-f', __DIR__ . '/fixtures.process/tick.php']);
	$process2 = Process::runExecutable(PHP_BINARY, ['-f', __DIR__ . '/fixtures.process/rev.php'], stdin: $process1);

	$output = '';
	$process2->wait(function ($stdOut) use (&$output) {
		$output .= $stdOut;
	});

	Assert::same('kcit' . PHP_EOL . 'kcit' . PHP_EOL . 'kcit' . PHP_EOL, $output);
});


test('cannot pipe from a process whose STDOUT is redirected', function () {
	$source = Process::runExecutable(PHP_BINARY, ['-r', 'echo "x";'], stdout: false);
	Assert::exception(
		fn() => Process::runExecutable(PHP_BINARY, ['-r', 'echo "y";'], stdin: $source),
		Nette\InvalidStateException::class,
		'Cannot pipe from the given process: %a%',
	);
});


test('chained piping A -> B -> C', function () {
	$a = Process::runExecutable(PHP_BINARY, ['-r', 'echo "abc";']);
	$b = Process::runExecutable(PHP_BINARY, ['-r', 'echo strtoupper(stream_get_contents(STDIN));'], stdin: $a);
	$c = Process::runExecutable(PHP_BINARY, ['-r', 'echo strrev(stream_get_contents(STDIN));'], stdin: $b);

	Assert::same('CBA', $c->getStdOutput());
});
