<?php

/**
 * Test: DateTime53 test.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Annotations;



require __DIR__ . '/../initialize.php';



date_default_timezone_set('Europe/Prague');

$obj = new DateTime53('Mon, 23 Jan 1978 10:00:00', new DateTimeZone('Europe/London'));

T::dump( $obj->format('Y-m-d H:i:s') );
T::dump( $obj->getTimezone()->getName() );
T::dump( $obj->getTimestamp() );

$obj = unserialize(serialize($obj));

T::dump( $obj->format('Y-m-d H:i:s') );
T::dump( $obj->getTimezone()->getName() );
T::dump( $obj->getTimestamp() );



$obj = new DateTime53(NULL, new DateTimeZone('Europe/London'));
$obj->setTimestamp(254400000);

T::dump( $obj->format('Y-m-d H:i:s') );
T::dump( $obj->getTimezone()->getName() );
T::dump( $obj->getTimestamp() );

$obj = unserialize(serialize($obj));

T::dump( $obj->format('Y-m-d H:i:s') );
T::dump( $obj->getTimezone()->getName() );
T::dump( $obj->getTimestamp() );



__halt_compiler() ?>

------EXPECT------
string(19) "1978-01-23 10:00:00"

string(13) "Europe/London"

int(254397600)

string(19) "1978-01-23 10:00:00"

string(13) "Europe/London"

int(254397600)

string(19) "1978-01-23 10:40:00"

string(13) "Europe/London"

int(254400000)

string(19) "1978-01-23 10:40:00"

string(13) "Europe/London"

int(254400000)
