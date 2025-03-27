<?php

// Outputs "hello" and "world" with a delay, allowing for incremental reading.

declare(strict_types=1);


function write($stream, string $text): void
{
	foreach (str_split($text) as $ch) {
		fwrite($stream, $ch);
		flush();
		usleep(50000);
	}
}


if ($argv[1] === 'stdout') {
	write(STDOUT, 'hello');
	write(STDOUT, 'world');
} elseif ($argv[1] === 'stderr') {
	write(STDERR, 'hello' . PHP_EOL);
	write(STDERR, 'world' . PHP_EOL);
} else {
	echo "Specify 'stdout' or 'stderr' as the first argument.";
	exit(1);
}
