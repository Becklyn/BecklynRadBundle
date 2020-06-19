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
     * @param int $numberOfItems
     * @param int $itemsPerPage
     */
    public function testInvalid (int $numberOfItems, int $itemsPerPage) : void
    {
        $this->expectException(\InvalidArgumentException::class);
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
        self::assertSame(true, $pagination->isValid());

        $newPagination = $pagination->withNumberOfItems(7);
        self::assertNotSame($pagination, $newPagination);
        self::assertSame(1, $newPagination->getCurrentPage());
        self::assertSame(1, $newPagination->getMaxPage());
        self::assertSame(false, $newPagination->isValid());
    }


    /**
     * Tests that even an invalid current page is preserved, for when the total number of items change.
     */
    public function testPreserveCurrentPage () : void
    {
        $pagination = new Pagination(5, 10, 25);
        self::assertSame(3, $pagination->getCurrentPage());

        $newPagination = $pagination->withNumberOfItems(100);
        self::assertSame(5, $newPagination->getCurrentPage());
    }


    /**
     * @return array
     */
    public function provideCurrent () : array
    {
        return [
            [3, 40, 3, true],
            [3, 10, 1, false],
            [3, 12, 2, false],
            [0, 12, 1, false],
        ];
    }


    /**
     * @dataProvider provideCurrent
     */
    public function testCurrent (int $currentPage, int $totalNumber, int $expectedCurrent, bool $expectedValid) : void
    {
        $pagination = new Pagination($currentPage, 10, $totalNumber);
        self::assertSame($expectedCurrent, $pagination->getCurrentPage());
        self::assertSame($expectedValid, $pagination->isValid());
    }


    /**
     *
     */
    public function testNormalization () : void
    {
        $pagination = new Pagination(2, 10, 29);

        self::assertEquals([
            "current" => 2,
            "min" => 1,
            "max" => 3,
            "next" => 3,
            "prev" => 1,
            "perPage" => 10,
            "total" => 29,
            "valid" => true,
        ], $pagination->toArray());
    }


    /**
     *
     */
    public function testCorrectCurrentNormalization () : void
    {
        $pagination = new Pagination(2, 1, 1);

        self::assertEquals([
            "current" => 1,
            "min" => 1,
            "max" => 1,
            "next" => null,
            "prev" => null,
            "perPage" => 1,
            "total" => 1,
            "valid" => false,
        ], $pagination->toArray());
    }


    /**
     *
     */
    public function testCorrectOffsetCalculation () : void
    {
        self::assertSame(0, (new Pagination(0, 10, 29))->getOffset());
        self::assertSame(0, (new Pagination(1, 10, 29))->getOffset());
        self::assertSame(10, (new Pagination(2, 10, 29))->getOffset());
        self::assertSame(10, (new Pagination(2, 10, 290))->getOffset());
        self::assertSame(5, (new Pagination(2, 5, 29))->getOffset());
        self::assertSame(20, (new Pagination(5, 5, 29))->getOffset());
    }
}
