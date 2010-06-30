<?php

/**
 * Test: Nette\Paginator Base:0 Page:-1 test.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Paginator;



require __DIR__ . '/../initialize.php';



$paginator = new Paginator;
$paginator->itemCount = 7;
$paginator->itemsPerPage = 6;
$paginator->base = 0;
$paginator->page = -1;

T::dump( $paginator->page );
T::dump( $paginator->offset );
T::dump( $paginator->countdownOffset );
T::dump( $paginator->length );



__halt_compiler() ?>

------EXPECT------
0

0

1

6
