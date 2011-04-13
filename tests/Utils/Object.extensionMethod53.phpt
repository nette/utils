<?php

/**
 * Test: Nette\Object extension method 5.3
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 * @phpversion 5.3
 */




require __DIR__ . '/../bootstrap.php';

require __DIR__ . '/Object.inc';



TestClass::extensionMethod('join',
	function (TestClass $that, $separator) {
		return $that->foo . $separator . $that->bar;
	}
);

$obj = new TestClass('Hello', 'World');
Assert::same( 'Hello*World', $obj->join('*') );
