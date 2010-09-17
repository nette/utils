<?php

/**
 * Test: Nette\Paginator ItemCount:0 test.
 *
 * @author     Petr Procházka
 * @package    Nette
 * @subpackage UnitTests
 */

use Nette\Paginator;



require __DIR__ . '/../bootstrap.php';



$paginator = new Paginator;

// ItemCount: 0
$paginator->setItemCount(0);
Assert::true( $paginator->isFirst() );
Assert::true( $paginator->isLast() );


// ItemCount: 1
$paginator->setItemCount(1);
Assert::true( $paginator->isFirst() );
Assert::true( $paginator->isLast() );


// ItemCount: 2
$paginator->setItemCount(2);
Assert::true( $paginator->isFirst() );
Assert::false( $paginator->isLast() );

// Page 2
$paginator->setPage(2);
Assert::false( $paginator->isFirst() );
Assert::true( $paginator->isLast() );
