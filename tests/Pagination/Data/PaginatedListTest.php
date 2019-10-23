<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Pagination\Data;

use Becklyn\RadBundle\Pagination\Data\PaginatedList;
use Becklyn\RadBundle\Pagination\Data\Pagination;
use PHPUnit\Framework\TestCase;

class PaginatedListTest extends TestCase
{
    /**
     *
     */
    public function testArray () : void
    {
        $pagination = new Pagination(1, 50, 10);
        $list = [1, 2, 3];

        $paginatedList = new PaginatedList($list, $pagination);

        self::assertSame($list, $paginatedList->getList());
        self::assertSame($pagination, $paginatedList->getPagination());
    }


    /**
     *
     */
    public function testIterator () : void
    {
        $pagination = new Pagination(1, 50, 10);
        $list = new \ArrayIterator([1, 2, 3]);

        $paginatedList = new PaginatedList($list, $pagination);
        self::assertSame($pagination, $paginatedList->getPagination());
    }


    /**
     *
     */
    public function testCreateFromList () : void
    {
        $list = PaginatedList::createFromArray(\range(0, 9));
        self::assertCount(10, $list->getList());
        self::assertSame(1, $list->getPagination()->getCurrentPage());
        self::assertSame(1, $list->getPagination()->getMaxPage());
        self::assertSame(10, $list->getPagination()->getPerPage());
    }


    /**
     *
     */
    public function testCreateFromEmptyList () : void
    {
        $list = PaginatedList::createFromArray([]);
        self::assertCount(0, $list->getList());
        self::assertSame(1, $list->getPagination()->getCurrentPage());
        self::assertSame(1, $list->getPagination()->getMaxPage());
        self::assertSame(1, $list->getPagination()->getPerPage());
    }
}
