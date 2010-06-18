<?php

/**
 * Test: Nette\Paginator ItemCount:0 test.
 *
 * @author     Petr Procházka
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Paginator;



require __DIR__ . '/../initialize.php';



$paginator = new Paginator;

T::note('ItemCount: 0');
$paginator->setItemCount(0);
T::dump( $paginator->isFirst() );
T::dump( $paginator->isLast() );

T::note('ItemCount: 1');
$paginator->setItemCount(1);
T::dump( $paginator->isFirst() );
T::dump( $paginator->isLast() );

T::note('ItemCount: 2');
$paginator->setItemCount(2);
T::dump( $paginator->isFirst() );
T::dump( $paginator->isLast() );
T::note('Page 2');
$paginator->setPage(2);
T::dump( $paginator->isFirst() );
T::dump( $paginator->isLast() );



__halt_compiler() ?>

------EXPECT------
ItemCount: 0

bool(TRUE)

bool(TRUE)

ItemCount: 1

bool(TRUE)

bool(TRUE)

ItemCount: 2

bool(TRUE)

bool(FALSE)

Page 2

bool(FALSE)

bool(TRUE)
