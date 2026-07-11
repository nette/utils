<?php declare(strict_types=1);

use Nette\Utils\Process;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('detached process survives destruction of the object', function () {
	$file = getTempDir() . '/detach-proof.txt';
	$process = Process::runExecutable(
		getPhpCliBinary(),
		['-r', 'usleep(1_000_000); file_put_contents($argv[1], "done");', $file],
		stdout: false,
		stderr: false,
	);
	$process->detach();

	$start = microtime(true);
	unset($process); // must neither terminate the process nor wait for it
	Assert::true(microtime(true) - $start < 0.5, 'destructor must not wait for the process');

	for ($i = 0; @file_get_contents($file) !== 'done' && $i < 100; $i++) { // @ - file may not exist yet
		usleep(50_000);
	}
	Assert::same('done', file_get_contents($file));
});


test('detached process can keep writing to a file target', function () {
	$file = getTempDir() . '/detach-out.txt';
	$process = Process::runExecutable(
		getPhpCliBinary(),
		['-r', 'usleep(200_000); echo "out";'],
		stdout: $file,
		stderr: false,
	);
	$process->detach();
	unset($process);

	for ($i = 0; @file_get_contents($file) !== 'out' && $i < 100; $i++) { // @ - file may not exist yet
		usleep(50_000);
	}
	Assert::same('out', file_get_contents($file));
});


test('detach() refuses a process whose output is captured in memory', function () {
	$process = Process::runExecutable(getPhpCliBinary(), ['-r', 'echo "hi";']);
	Assert::exception(
		fn() => $process->detach(),
		Nette\InvalidStateException::class,
		'Cannot detach process: its output is captured in memory, pass a file name, a resource or false as $stdout/$stderr.',
	);
});
