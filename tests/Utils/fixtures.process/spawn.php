<?php declare(strict_types=1);

// Starts a PHP child that inherits STDOUT and STDERR and sleeps for <seconds>, writes 'parent' and exits at once.

$child = proc_open([PHP_BINARY, '-r', 'usleep((int) (' . (float) $argv[1] . ' * 1e6));'], [STDIN, STDOUT, STDERR], $pipes);
echo 'parent';
