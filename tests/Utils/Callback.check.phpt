<?php

/**
 * Test: Nette\Utils\Callback::check()
 */

declare(strict_types=1);

use Nette\Utils\Callback;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


Assert::same('trim', Callback::check('trim'));

Assert::same('undefined', Callback::check('undefined', true));


Assert::exception(
	fn() => Callback::check(123, true),
	Nette\InvalidArgumentException::class,
	'Given value is not a callable type.',
);


Assert::exception(
	fn() => Callback::check('undefined'),
	Nette\InvalidArgumentException::class,
	"Callback 'undefined' is not callable.",
);


// PHP bugs - is_callable($object, true) fails
Assert::exception(
	fn() => Callback::check(new stdClass),
	Nette\InvalidArgumentException::class,
	"Callback 'stdClass::__invoke' is not callable.",
);

Assert::exception(
	fn() => Callback::check(new stdClass, true),
	Nette\InvalidArgumentException::class,
	'Given value is not a callable type.',
);
