<?php

declare(strict_types=1);

while ($line = fgets(STDIN)) {
	echo strrev(trim($line)) . PHP_EOL;
}
