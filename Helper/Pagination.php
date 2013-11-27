<?php

namespace Becklyn\RadBundle\Helper;


use Doctrine\ORM\QueryBuilder;

class Pagination
{
    /**
     * The total number of items
     *
     * @var int
     */
    private $numberOfItems = 0;


    /**
     * The items per page
     *
     * @var int
     */
    private $itemsPerPage;


    /**
     * The current page (as externally set)
     *
     * @var int
     */
    private $currentPage = null;


    /**
     * The min page
     *
     * @var int
     */
    private $minPage;



    /**
     * @param int $currentPage
     * @param int $itemsPerPage
     * @param int $minPage
     */
    public function  __construct ($currentPage = 1, $itemsPerPage = 25, $minPage = 1)
    {
        $this->setCurrentPage($currentPage);
        $this->setItemsPerPage($itemsPerPage);
        $this->setMinPage($minPage);
    }



    /**
     * @return int
     */
    public function getItemsPerPage ()
    {
        return $this->itemsPerPage;
    }



    /**
     * @return int
     */
    public function getNumberOfItems ()
    {
        return $this->numberOfItems;
    }



    /**
     * @param int $itemsPerPage
     *
     * @throws \InvalidArgumentException
     */
    public function setItemsPerPage ($itemsPerPage)
    {
        $itemsPerPage = (int) $itemsPerPage;

        if (0 >= $itemsPerPage)
        {
            throw new \InvalidArgumentException("Items per page must be > 0.");
        }

        $this->itemsPerPage = $itemsPerPage;
    }



    /**
     * @param int $numberOfItems
     *
     * @throws \InvalidArgumentException
     */
    public function setNumberOfItems ($numberOfItems)
    {
        $numberOfItems = (int) $numberOfItems;

        if ($numberOfItems < 0)
        {
            throw new \InvalidArgumentException("Number of items must be >= 0.");
        }

        $this->numberOfItems = $numberOfItems;
    }



    /**
     * @return int
     */
    public function getMaxPage ()
    {
        if (0 === $this->numberOfItems)
        {
            return $this->minPage;
        }

        return (int) (ceil($this->numberOfItems / $this->itemsPerPage) - 1 + $this->minPage);
    }



    /**
     * @param int $minPage
     */
    public function setMinPage ($minPage)
    {
        $this->minPage = (int) $minPage;
    }



    /**
     * @return int
     */
    public function getMinPage ()
    {
        return $this->minPage;
    }



    /**
     * @param int $currentPage
     */
    public function setCurrentPage ($currentPage)
    {
        $this->currentPage = (int) $currentPage;
    }



    /**
     * @return int
     */
    public function getCurrentPage ()
    {
        if ($this->isValidPage($this->currentPage))
        {
            return $this->currentPage;
        }

        return $this->minPage;
    }



    /**
     * Returns, whether the given page is valid
     *
     * @param int $page
     *
     * @return bool
     */
    private function isValidPage ($page)
    {
        return is_int($page)
            && ($page >= $this->getMinPage())
            && ($page <= $this->getMaxPage());
    }



    /**
     * Returns the next page
     *
     * @return int|null
     */
    public function getNextPage ()
    {
        $currentPage = $this->getCurrentPage();

        if ($currentPage < $this->getMaxPage())
        {
            return $currentPage + 1;
        }

        return null;
    }



    /**
     * Returns the previous page
     *
     * @return int|null
     */
    public function getPreviousPage ()
    {
        $currentPage = $this->getCurrentPage();

        if ($currentPage > $this->getMinPage())
        {
            return $currentPage - 1;
        }

        return null;
    }



    /**
     * Returns the grouped pagination pages
     */
    public function getGroupedPaginationPages ($aroundCurrent = 1, $aroundBorder = 1)
    {
        $pages = array();

        $minPage = $this->getMinPage();
        $maxPage = $this->getMaxPage();

        $addPage = function ($page) use (&$pages, $minPage, $maxPage)
        {
            if (is_int($page) && ($page >= $minPage) && ($page <= $maxPage))
            {
                $pages[$page] = true;
            }
        };

        // add pages around min border
        for ($i = 0; $i <= $aroundBorder; $i++)
        {
            $addPage($this->getMinPage() + $i);
        }

        // add pages around current
        for ($i = -$aroundCurrent; $i <= $aroundCurrent; $i++)
        {
            $addPage($this->getCurrentPage() + $i);
        }

        // add pages around max border
        for ($i = -$aroundBorder; $i <= 0; $i++)
        {
            $addPage($this->getMaxPage() + $i);
        }

        ksort($pages);
        return array_keys($pages);
    }



    /**
     * Returns all pagination pages
     *
     * @return array
     */
    public function getAllPaginationPages ()
    {
        return range($this->getMinPage(), $this->getMaxPage(), 1);
    }
}