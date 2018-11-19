<?php declare(strict_types=1);

namespace Becklyn\RadBundle\tests\Pagination;

use Becklyn\RadBundle\Pagination\PaginatedList;
use Becklyn\RadBundle\Pagination\Pagination;
use PHPUnit\Framework\TestCase;


class PaginatedListTest extends TestCase
{
    public function testAccessors () : void
    {
        $pagination = new Pagination(1, 10);
        $list = [1,2,3];

        $paginatedList = new PaginatedList($list, $pagination);

        self::assertSame($list, $paginatedList->getList());
        self::assertSame($pagination, $paginatedList->getPagination());
    }
}
