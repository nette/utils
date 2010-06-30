<?php

/**
 * Test: Nette\Paginator Base:1 test.
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
$paginator->base = 1;
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
2

2

1

2

6

0

1
