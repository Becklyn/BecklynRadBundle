<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Pagination;

use Becklyn\RadBundle\Pagination\PaginatedList;
use Becklyn\RadBundle\Pagination\Pagination;
use PHPUnit\Framework\TestCase;

class PaginatedListTest extends TestCase
{
    public function testArray () : void
    {
        $pagination = new Pagination(1, 10);
        $list = [1, 2, 3];

        $paginatedList = new PaginatedList($list, $pagination);

        static::assertSame($list, $paginatedList->getList());
        static::assertSame($pagination, $paginatedList->getPagination());
    }


    public function testIterator () : void
    {
        $pagination = new Pagination(1, 10);
        $list = new \ArrayIterator([1, 2, 3]);

        $paginatedList = new PaginatedList($list, $pagination);
        static::assertSame($pagination, $paginatedList->getPagination());
    }
}
