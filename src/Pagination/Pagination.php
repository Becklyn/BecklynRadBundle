<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Pagination;


/**
 * Generic pagination calculator
 */
class Pagination
{
    /**
     * @var int
     */
    private $currentPage;


    /**
     * @var int
     */
    private $numberOfItems;


    /**
     * @var int
     */
    private $itemsPerPage;


    /**
     * @param int $currentPage
     * @param int $maxPage
     */
    public function __construct (int $currentPage, int $numberOfItems, int $itemsPerPage = 50)
    {
        if ($itemsPerPage <= 0)
        {
            throw new \InvalidArgumentException("Pagination can only be created for at least 1 item per page.");
        }

        if ($numberOfItems < 0)
        {
            throw new \InvalidArgumentException("Pagination can only be created for a positive number of items");
        }

        $this->currentPage = $currentPage;
        $this->numberOfItems = $numberOfItems;
        $this->itemsPerPage = $itemsPerPage;
        $this->maxPage = \max(1, (int) \ceil($numberOfItems / $itemsPerPage));
    }


    /**
     * @return int
     */
    public function getCurrentPage () : int
    {
        return $this->currentPage;
    }


    /**
     * @return int
     */
    public function getMinPage () : int
    {
        return 1;
    }


    /**
     * @return int
     */
    public function getMaxPage () : int
    {
        return $this->maxPage;
    }


    /**
     * @return bool
     */
    public function isValidCurrentPage () : bool
    {
        return (1 <= $this->currentPage && $this->currentPage <= $this->maxPage);
    }


    /**
     * @return int|null
     */
    public function getNextPage () : ?int
    {
        return $this->currentPage < $this->maxPage
            ? $this->currentPage + 1
            : null;
    }


    /**
     * @return int|null
     */
    public function getPreviousPage () : ?int
    {
        return $this->currentPage > 1
            ? $this->currentPage - 1
            : null;
    }


    /**
     * @return array
     */
    public function toArray () : array
    {
        return [
            "current" => $this->currentPage,
            "min" => $this->getMinPage(),
            "max" => $this->maxPage,
            "next" => $this->getNextPage(),
            "prev" => $this->getPreviousPage(),
            "valid" => $this->isValidCurrentPage(),
        ];
    }
}
