<?php declare(strict_types=1);

use Nette\Utils\{Helpers, Process};
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// Captured output while the process keeps writing and the caller keeps reading. On Windows with PHP < 8.5 the output
// goes through temporary files; reading them must not disturb the writing child.

$pattern = function (int $bytes, bool $reverse): string {
	$block = implode(array_map('chr', range(0, 255)));
	return substr(str_repeat($reverse ? strrev($block) : $block, intdiv($bytes, 256) + 1), 0, $bytes);
};

// an own temporary directory, other tests run in parallel
$tempDir = getTempDir();
putenv("TMP=$tempDir");
putenv("TEMP=$tempDir");
putenv("TMPDIR=$tempDir");
Assert::same(realpath($tempDir), realpath(sys_get_temp_dir()));
$temporaryFiles = fn() => glob("$tempDir/*") ?: [];
$before = [];

// 1 MiB per stream in 16 KiB writes with pauses: the caller reads many times while the child writes
$start = fn() => Process::runExecutable(
	getPhpCliBinary(),
	['-f', __DIR__ . '/fixtures.process/binary.php', (string) (1 << 20), (string) (1 << 20), '16384', '100'],
);


test('final output collection is binary-exact on both streams', function () use ($start, $pattern) {
	$process = $start();
	Assert::same(md5($pattern(1 << 20, false)), md5($process->getStdOutput()));
	Assert::same(md5($pattern(1 << 20, true)), md5($process->getStdError()));
});


test('incremental output collection is binary-exact on both streams', function () use ($start, $pattern) {
	$process = $start();
	$output = $error = '';
	$reads = 0;
	while ($process->isRunning()) {
		$output .= $process->consumeStdOutput();
		$error .= $process->consumeStdError();
		$reads++;
		usleep(1000);
	}
	$output .= $process->consumeStdOutput();
	$error .= $process->consumeStdError();

	Assert::true($reads > 10);
	Assert::same(strlen($pattern(1 << 20, false)), strlen($output));
	Assert::same(md5($pattern(1 << 20, false)), md5($output));
	Assert::same(md5($pattern(1 << 20, true)), md5($error));
});


test('wait() with a callback receives exact output', function () use ($start, $pattern) {
	$process = $start();
	$output = $error = '';
	$process->wait(function (string $out, string $err) use (&$output, &$error) {
		$output .= $out;
		$error .= $err;
	});
	Assert::same(md5($pattern(1 << 20, false)), md5($output));
	Assert::same(md5($pattern(1 << 20, true)), md5($error));
});


test('temporary files are removed', function () use ($temporaryFiles, $before) {
	$process = Process::runExecutable(getPhpCliBinary(), ['-r', 'echo "x";']);
	Assert::same('x', $process->getStdOutput());
	$process->terminate();
	Assert::same([], array_diff($temporaryFiles(), $before));
});


if (Helpers::IsWindows) { // POSIX starts the child and reports the missing executable by its exit code
	test('temporary files are removed when the process cannot be started', function () use ($temporaryFiles, $before) {
		Assert::exception(
			fn() => Process::runExecutable(__DIR__ . '/nonexistent-' . uniqid()),
			Nette\Utils\ProcessFailedException::class,
		);
		Assert::same([], array_diff($temporaryFiles(), $before));
	});
}


test('temporary files are removed when a later output cannot be opened', function () use ($temporaryFiles, $before) {
	Assert::exception(
		fn() => Process::runExecutable(getPhpCliBinary(), ['-r', 'echo 1;'], stderr: __DIR__ . '/nonexistent-dir/x.txt'),
		Nette\IOException::class,
	);
	Assert::same([], array_diff($temporaryFiles(), $before));
});


test('a descendant holding the output does not leave the temporary file behind', function () use ($temporaryFiles, $before) {
	$process = Process::runExecutable(getPhpCliBinary(), ['-f', __DIR__ . '/fixtures.process/spawn.php', '1']);
	Assert::same('parent', $process->getStdOutput());
	usleep(1_500_000); // the descendant has ended
	$process = null;
	Assert::same([], array_diff($temporaryFiles(), $before));
});


if (Helpers::IsWindows && PHP_VERSION_ID < 80500) {
	test('temporary files are removed from the directory while the process runs', function () use ($temporaryFiles, $before) {
		// the removal right after the start is best effort; where it succeeds, the open handles keep working
		$process = Process::runExecutable(getPhpCliBinary(), ['-r', 'usleep(300000); echo "late";']);
		Assert::same([], array_diff($temporaryFiles(), $before));
		Assert::same('late', $process->getStdOutput());
	});
}


test('output appended after the reader reached the end is read by later calls', function () {
	// the reader repeatedly reaches the current end of a growing output, which must not stop later reads
	$process = Process::runExecutable(getPhpCliBinary(), ['-r', 'for ($i = 0; $i < 10; $i++) { fwrite(STDOUT, "x$i"); usleep(100_000); }']);
	$chunks = [];
	while ($process->isRunning()) {
		if (($chunk = $process->consumeStdOutput()) !== '') {
			$chunks[] = $chunk;
		}
		usleep(20_000);
	}
	$chunks[] = $process->consumeStdOutput();
	Assert::same('x0x1x2x3x4x5x6x7x8x9', implode($chunks));
	Assert::true(count(array_filter($chunks)) >= 5, 'output should arrive in several chunks');
});


test('an error handler that throws does not affect the handling of temporary files', function () use ($temporaryFiles, $before) {
	set_error_handler(fn(int $severity, string $message) => throw new ErrorException($message, 0, $severity));
	try {
		$process = Process::runExecutable(getPhpCliBinary(), ['-r', 'echo "ok";']);
		Assert::same('ok', $process->getStdOutput());
		$process = null;
	} finally {
		restore_error_handler();
	}
	Assert::same([], array_diff($temporaryFiles(), $before));
});


if (Helpers::IsWindows && PHP_VERSION_ID < 80500) {
	$acl = function (string $dir, string $args): void {
		exec('icacls ' . escapeshellarg($dir) . " $args 2>&1", $output, $code);
		Assert::same(0, $code, implode("\n", $output));
	};
	$user = escapeshellarg((string) getenv('USERNAME'));


	test('a failure to create a temporary file is reported', function () use ($tempDir, $temporaryFiles, $acl, $user) {
		$acl($tempDir, "/deny $user:(WD)"); // no new files in the directory
		set_error_handler(fn(int $severity, string $message) => throw new ErrorException($message, 0, $severity));
		try {
			Assert::exception(
				fn() => Process::runExecutable(getPhpCliBinary(), ['-r', 'echo 1;']),
				Nette\Utils\ProcessFailedException::class,
				'Unable to create a temporary file for the output: %a%',
			);
		} finally {
			restore_error_handler();
			$acl($tempDir, "/remove:d $user");
		}
		Assert::same([], $temporaryFiles());
	});


	test('a temporary file that cannot be removed stays silently', function () use ($tempDir, $temporaryFiles, $before, $acl, $user) {
		$acl($tempDir, "/deny $user:(OI)(DE) /deny $user:(DC)"); // files in the directory cannot be deleted
		set_error_handler(fn(int $severity, string $message) => throw new ErrorException($message, 0, $severity));
		try {
			$process = Process::runExecutable(getPhpCliBinary(), ['-r', 'echo "kept";']);
			Assert::same('kept', $process->getStdOutput());
			$process = null; // removal after the start, in close() and in the destructor all fail
			$left = array_diff($temporaryFiles(), $before);
		} finally {
			restore_error_handler();
			$acl($tempDir, "/remove:d $user");
		}
		Assert::count(2, $left); // STDOUT and STDERR
		foreach ($left as $file) {
			unlink($file);
		}
	});
}
