<?php

/**
 * Test: Nette\Paginator Base:0 Page:3 test.
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
$paginator->page = 3;

T::dump( $paginator->page );
T::dump( $paginator->pageCount );
T::dump( $paginator->firstPage );
T::dump( $paginator->lastPage );
T::dump( $paginator->offset );
T::dump( $paginator->countdownOffset );
T::dump( $paginator->length );



__halt_compiler() ?>

------EXPECT------
1

2

0

1

6

0

1
