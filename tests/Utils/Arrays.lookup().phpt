<?php

/**
 * Test: Nette\Utils\Arrays::lookup()
 */

use Nette\Utils\Arrays,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


$arr  = array(
	'' => 0,
	'lvl1' => array(
		'lvl2' => array(
			'lvl3' => 'ok'
		)
	),
	1 => array(
		2 => array(
			3 => 'ok2'
		)
	),
	'single' => 'alone'
);

test(function() use ($arr) {
	Assert::same(0, Arrays::lookup($arr, ''));
	Assert::same('alone', Arrays::lookup($arr, 'single'));
	Assert::same(null, Arrays::lookup($arr, 'unknown'));
	Assert::same('ok', Arrays::lookup($arr, 'lvl1.lvl2.lvl3'));
	Assert::same('ok2', Arrays::lookup($arr, '1.2.3'));
	Assert::same(array('lvl3' => 'ok'), Arrays::lookup($arr, 'lvl1.lvl2'));
	Assert::same('unknown', Arrays::lookup($arr, 'lvl1.lvl2.undefined', 'unknown'));
});
