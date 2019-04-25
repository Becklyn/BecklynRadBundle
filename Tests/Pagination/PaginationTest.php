<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Pagination;

use Becklyn\RadBundle\Pagination\Pagination;
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
        $pagination = new Pagination(1, $numberOfItems, $itemsPerPage);
        static::assertSame($expectedMaxPage, $pagination->getMaxPage());
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
        new Pagination(1, $numberOfItems, $itemsPerPage);
    }
}
