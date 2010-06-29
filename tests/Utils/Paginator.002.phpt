<?php

/**
 * Test: Nette\Paginator Base:0 Page:-1 PerPage:7 test.
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
$paginator->itemsPerPage = 7;
$paginator->base = 0;
$paginator->page = -1;

T::dump( $paginator->page );
T::dump( $paginator->pageCount );
T::dump( $paginator->firstPage );
T::dump( $paginator->lastPage );
T::dump( $paginator->offset );
T::dump( $paginator->countdownOffset );
T::dump( $paginator->length );



__halt_compiler() ?>

------EXPECT------
int(0)

int(1)

int(0)

int(0)

int(0)

int(0)

int(7)
