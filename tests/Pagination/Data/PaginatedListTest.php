<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Pagination\Data;

use Becklyn\RadBundle\Pagination\Data\PaginatedList;
use Becklyn\RadBundle\Pagination\Data\Pagination;
use PHPUnit\Framework\TestCase;

class PaginatedListTest extends TestCase
{
    public function testArray () : void
    {
        $pagination = new Pagination(1, 50, 10);
        $list = [1, 2, 3];

        $paginatedList = new PaginatedList($list, $pagination);

        self::assertSame($list, $paginatedList->getList());
        self::assertSame($pagination, $paginatedList->getPagination());
    }


    public function testIterator () : void
    {
        $pagination = new Pagination(1, 50, 10);
        $list = new \ArrayIterator([1, 2, 3]);

        $paginatedList = new PaginatedList($list, $pagination);
        self::assertSame($pagination, $paginatedList->getPagination());
    }
}
