<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Pagination\Data;

use Becklyn\RadBundle\Pagination\Data\Pagination;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    /**
     * @return array
     */
    public function provideValidMaxPage () : array
    {
        return [
            [100, 10, 10],
            [9, 10, 1],
            [10, 10, 1],
            [11, 10, 2],
            [10, 1, 10],
            [1, 1, 1],
            [0, 1, 1],
        ];
    }


    /**
     * @dataProvider provideValidMaxPage
     *
     * @param int $numberOfItems
     * @param int $itemsPerPage
     * @param int $expectedMaxPage
     */
    public function testValidMaxPage (int $numberOfItems, int $itemsPerPage, int $expectedMaxPage) : void
    {
        $pagination = new Pagination(1, $itemsPerPage, $numberOfItems);
        self::assertSame($expectedMaxPage, $pagination->getMaxPage());
    }


    /**
     * @return array
     */
    public function provideInvalid () : array
    {
        return [
            [-1, 10],
            [10, 0],
            [10, -1],
        ];
    }


    /**
     * @dataProvider provideInvalid
     *
     * @expectedException \InvalidArgumentException
     *
     * @param int $numberOfItems
     * @param int $itemsPerPage
     */
    public function testInvalid (int $numberOfItems, int $itemsPerPage) : void
    {
        new Pagination(1, $itemsPerPage, $numberOfItems);
    }


    /**
     *
     */
    public function testCreateWithNewCount () : void
    {
        $pagination = new Pagination(2, 10, 25);
        self::assertSame(2, $pagination->getCurrentPage());
        self::assertSame(3, $pagination->getMaxPage());
        self::assertSame(25, $pagination->getNumberOfItems());

        $newPagination = $pagination->withNumberOfItems(7);
        self::assertNotSame($pagination, $newPagination);
        self::assertSame(1, $newPagination->getCurrentPage());
        self::assertSame(1, $newPagination->getMaxPage());
        self::assertSame(7, $newPagination->getNumberOfItems());

    }
}
