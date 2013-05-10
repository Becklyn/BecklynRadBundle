<?php

namespace OAGM\BaseBundle\Tests\Helper;

use OAGM\BaseBundle\Helper\Pagination;

class PaginationTest extends \PHPUnit_Framework_TestCase
{
    //region All Pages functionality
    /**
     * Data provider for testAllPages
     *
     * @return array
     */
    public function allPagesProvider ()
    {
        return array(
            array(5, array(1, 2, 3, 4, 5)),
            array(0, array(1)),
        );
    }

    /**
     * Tests the all pages calculation
     *
     * @dataProvider allPagesProvider
     */
    public function testAllPages ($numberOfItems, $expectedPages)
    {
        $pagination = new Pagination(1, 1, 1);
        $pagination->setNumberOfItems($numberOfItems);

        $this->assertSame($expectedPages, $pagination->getAllPaginationPages());
    }
    //endregion



    //region Grouped Pages functionality
    /**
     * Data provider for testGroupedPages
     *
     * @return array
     */
    public function groupedPagesProvider ()
    {
        return array(
            // normal cases
            array( 5, 3, 0, 0, array(1, 3, 5)),
            array( 5, 1, 0, 0, array(1, 5)),

            // around current
            array(10, 5, 1, 0, array(1, 4, 5, 6, 10)),

            // around border
            array(10, 5, 0, 1, array(1, 2, 5, 9, 10)),

            // around current + border
            array(10, 5, 1, 1, array(1, 2, 4, 5, 6, 9, 10)),

            // too large border
            array( 5, 3, 4, 4, array(1, 2, 3, 4, 5)),

            // empty list
            array( 0, 1, 0, 0, array(1)),
        );
    }



    /**
     * Tests the grouped pages calculation
     *
     * @dataProvider groupedPagesProvider
     */
    public function testGroupedPages ($numberOfItems, $currentPage, $aroundCurrent, $aroundBorder, $expectedPages)
    {
        $pagination = new Pagination($currentPage, 1, 1);
        $pagination->setNumberOfItems($numberOfItems);

        $this->assertSame($expectedPages, $pagination->getGroupedPaginationPages($aroundCurrent, $aroundBorder));
    }
    //endregion



    //region Basic functionality and calculation tests
    /**
     * Tests for the max page on a full page
     */
    public function testMaxPageCalculationBeforeBorder ()
    {
        $minPage = 1;
        $pagination = new Pagination(1, 5, $minPage);
        $pagination->setNumberOfItems(5);

        $this->assertSame($minPage, $pagination->getMaxPage());
    }



    /**
     * Tests for the max page on a full + 1 page
     */
    public function testMaxPageCalculationAfterBorder ()
    {
        $minPage = 1;
        $pagination = new Pagination(1, 5, $minPage);
        $pagination->setNumberOfItems(6);

        $this->assertSame($minPage + 1, $pagination->getMaxPage());
    }



    /**
     * Tests the empty list
     */
    public function testEmptyList ()
    {
        $pagination = new Pagination(1, 5, 1);

        $this->assertSame(1, $pagination->getMinPage());
        $this->assertSame(1, $pagination->getMaxPage());
    }



    /**
     * Validate all cases of the current page
     */
    public function testCurrentPage ()
    {
        $minPage = 1;
        $pagination = new Pagination(1, 5, $minPage);
        $pagination->setNumberOfItems(6);

        // initial value
        $this->assertSame(1, $pagination->getCurrentPage());

        // set to valid page
        $pagination->setCurrentPage(2);
        $this->assertSame(2, $pagination->getCurrentPage());

        // set to currently invalid page
        $pagination->setCurrentPage(3);
        $this->assertSame($minPage, $pagination->getCurrentPage());

        // increase number of items, so that page 3 is now valid
        $pagination->setNumberOfItems(15);
        $this->assertSame(3, $pagination->getCurrentPage());
    }



    /**
     * Tests the max page after changed items per page
     */
    public function testMaxPageAfterChangeOfItemsPerPage ()
    {
        $pagination = new Pagination(1, 5, 1);
        $pagination->setNumberOfItems(20);

        $this->assertSame(4, $pagination->getMaxPage());

        // change items per page and check again
        $pagination->setItemsPerPage(1);
        $this->assertSame(20, $pagination->getMaxPage());
    }



    /**
     * Tests the next page values
     */
    public function testNextPage ()
    {
        $pagination = new Pagination(1, 1, 1);
        $pagination->setNumberOfItems(2);

        $this->assertSame(2, $pagination->getNextPage());

        // no next page
        $pagination->setCurrentPage($pagination->getMaxPage());
        $this->assertNull($pagination->getNextPage());
    }



    /**
     * Tests the previous page values
     */
    public function testPreviousPage ()
    {
        $pagination = new Pagination(2, 1, 1);
        $pagination->setNumberOfItems(2);

        $this->assertSame(1, $pagination->getPreviousPage());

        // no previous page
        $pagination->setCurrentPage($pagination->getMinPage());
        $this->assertNull($pagination->getPreviousPage());
    }
    //endregion



    //region Basic "Number Of Items" tests
    /**
     * Test valid number of items cases, set in setter
     */
    public function testNumberOfItemsSetter ()
    {
        $pagination = new Pagination();
        $pagination->setNumberOfItems(100);
        $this->assertSame(100, $pagination->getNumberOfItems());
    }



    /**
     * Test invalid number of items
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidNumberOfItems ()
    {
        $pagination = new Pagination();
        $pagination->setNumberOfItems(-1);
    }
    //endregion



    //region Basic "Min Page" tests
    /**
     * Test valid min page, set in setter
     */
    public function testMinPageSetter ()
    {
        $pagination = new Pagination(1, 25, 1);
        $this->assertSame(1, $pagination->getMinPage());
    }



    /**
     * Test for valid number of items, set in constructor
     */
    public function testMinPageConstructor ()
    {
        $pagination = new Pagination(1, 25, 1);
        $pagination->setMinPage(2);

        $this->assertSame(2, $pagination->getMinPage());
    }
    //endregion



    //region Basic "Items Per Page" tests
    /**
     * Tests for items per page, set in constructor
     */
    public function testItemsPerPageConstructor ()
    {
        $pagination = new Pagination(1, 25);
        $this->assertSame(25, $pagination->getItemsPerPage());
    }



    /**
     * Tests for items per page, set in setter
     */
    public function testItemsPerPageSetter ()
    {
        $pagination = new Pagination(1, 25);
        $pagination->setItemsPerPage(5);
        $this->assertSame(5, $pagination->getItemsPerPage());
    }



    /**
     * Tests for invalid items per page
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidItemsPerPage ()
    {
        new Pagination(1, 0);
    }
    //endregion
}