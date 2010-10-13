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



// entering
Tools::enterCriticalSection();

// leaving
Tools::leaveCriticalSection();

try {
	// leaving not entered
	Tools::leaveCriticalSection();
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', 'Critical section has not been initialized.', $e );
}

try {
	// doubled entering
	Tools::enterCriticalSection();
	Tools::enterCriticalSection();
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', 'Critical section has already been entered.', $e );
}
