<?php

/**
 * Test: Nette\Tools critical sections.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Tools;



require __DIR__ . '/../bootstrap.php';



$key = '../' . implode('', range("\x00", "\x1F"));

// temporary directory
define('TEMP_DIR', __DIR__ . '/tmp');
Nette\Environment::setVariable('tempDir', TEMP_DIR);
TestHelpers::purge(TEMP_DIR);


// entering
Tools::enterCriticalSection($key);

// leaving
Tools::leaveCriticalSection($key);

try {
	// leaving not entered
	Tools::leaveCriticalSection('notEntered');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', 'Critical section has not been initialized.', $e );
}
