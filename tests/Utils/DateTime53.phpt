<?php

/**
 * Test: DateTime53 test.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Annotations;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



date_default_timezone_set('Europe/Prague');

$obj = new DateTime53('Mon, 23 Jan 1978 10:00:00', new DateTimeZone('Europe/London'));

dump( $obj->format('Y-m-d H:i:s') );
dump( $obj->getTimezone()->getName() );
dump( $obj->getTimestamp() );

$obj = unserialize(serialize($obj));

dump( $obj->format('Y-m-d H:i:s') );
dump( $obj->getTimezone()->getName() );
dump( $obj->getTimestamp() );



$obj = new DateTime53(NULL, new DateTimeZone('Europe/London'));
$obj->setTimestamp(254400000);

dump( $obj->format('Y-m-d H:i:s') );
dump( $obj->getTimezone()->getName() );
dump( $obj->getTimestamp() );

$obj = unserialize(serialize($obj));

dump( $obj->format('Y-m-d H:i:s') );
dump( $obj->getTimezone()->getName() );
dump( $obj->getTimestamp() );



__halt_compiler();

------EXPECT------
string(19) "1978-01-23 10:00:00"

string(13) "Europe/London"

int(254397600)

string(19) "1978-01-23 10:00:00"

string(13) "Europe/London"

int(254397600)

string(19) "1978-01-23 11:40:00"

string(13) "Europe/London"

int(254403600)

string(19) "1978-01-23 11:40:00"

string(13) "Europe/London"

int(254403600)
