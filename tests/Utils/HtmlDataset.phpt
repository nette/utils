<?php

/**
 * Test: Nette\Utils\HtmlDataset
 *
 * @author     Petr Morávek <petr@pada.cz>
 */

use Nette\Utils\HtmlDataset,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() {
	$d = new HtmlDataset;
	$d['string'] = 'string';
	$d['empty'] = '';
	$d['null'] = null;
	$d['true'] = true;
	$d['false'] = false;
	$d['int'] = 42;
	$d->list = array(1, 2);
	$d->dict = array('a' => 'A', 1 => 2);
	$obj = new \stdClass;
	$obj->a = 'A';
	$obj->b = 'B';
	$d->obj = $obj;

	Assert::same( 'data-string="string" data-empty="" data-true="true" data-false="false" data-int="42" data-list="[1,2]" data-dict=\'{"a":"A","1":2}\' data-obj=\'{"a":"A","b":"B"}\'', (string) $d );
	Assert::same( 8, count($d) );
	Assert::type( '\ArrayIterator', $d->getIterator() );
});


test(function() {
	$d = new HtmlDataset(array('TestAttr' => false, 'testAttr' => true));
	foreach (array('testAttr', 'test-attr', 'TestAttr', 'Test-Attr') as $name) {
		Assert::true( isset($d->$name) );
		Assert::true( $d->$name );

		Assert::true( isset($d[$name]) );
		Assert::true( $d[$name] );
	}

	Assert::false( isset($d->unknown) );
	Assert::false( isset($d['unknown']) );
});


test(function() {
	$d = new HtmlDataset(array('a-b' => true, 'c-d' => true));

	Assert::true( isset($d->aB) );
	Assert::true( isset($d['cD']) );

	unset($d->aB);
	unset($d['cD']);

	Assert::false( isset($d->aB) );
	Assert::false( isset($d['cD']) );
});
