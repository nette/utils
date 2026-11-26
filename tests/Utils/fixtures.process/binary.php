<?php declare(strict_types=1);

// Writes a binary pattern (all 256 byte values) to STDOUT and its reverse to STDERR, interleaved in chunks.
// Arguments: <stdout bytes> <stderr bytes> [chunk size = 65536] [pause in µs after each chunk = 0]

[, $outBytes, $errBytes, $chunk, $pause] = $argv + [3 => 65536, 4 => 0];
$block = implode(array_map('chr', range(0, 255)));
$pattern = fn(string $block, int $bytes) => substr(str_repeat($block, intdiv($bytes, 256) + 1), 0, $bytes);
$streams = [1 => STDOUT, 2 => STDERR];
$left = [1 => $pattern($block, (int) $outBytes), 2 => $pattern(strrev($block), (int) $errBytes)];

while ($left = array_filter($left, fn($data) => $data !== '')) {
	foreach ($left as $id => $data) {
		fwrite($streams[$id], substr($data, 0, (int) $chunk));
		$left[$id] = substr($data, (int) $chunk);
	}
	usleep((int) $pause);
}
