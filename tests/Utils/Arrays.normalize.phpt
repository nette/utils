<?php

/**
 * Test: Nette\Utils\Arrays::searchKey()
 */

use Nette\Utils\Arrays,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same(
	array(
		'first' => NULL,
		'a' => 'second',
		'd' => array('third'),
		'fourth' => NULL,
	),
	Arrays::normalize(array(
		1 => 'first',
		'a' => 'second',
		'd' => array('third'),
		7 => 'fourth'
	))
);


Assert::same(
	array(
		'first' => TRUE,
		'' => 'second',
	),
	Arrays::normalize(array(
		1 => 'first',
		'' => 'second',
	), TRUE)
);
