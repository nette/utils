<?php

/**
 * Test: Nette\Utils\Strings::cut()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';



test(function () {
	$path = '/some/path/in/system/file.test';
	Assert::same('some/path/in/system/file.test', Strings::cut($path, '/', 1));
	Assert::same('file.test', Strings::cut($path, '/', -1));
	Assert::same('/some/path/in/system', Strings::cut($path, '/', -1, true));
	Assert::same('/some/path/in/system/', Strings::cut($path, '/', -1, true, true));
	Assert::same('/some/path', Strings::cut($path, '/', 3, true));
	Assert::same('/some/path', Strings::cut($path, '/', -3, true, false));

	Assert::same('/some/path/in/system/file', Strings::cut($path, '.test', -1, true));
	Assert::same('/system/file.test', Strings::cut($path, 'in', 1));

	Assert::same($path, Strings::cut($path, '/', 0));

	Assert::false(Strings::cut($path, 'not-in-string'));
});
