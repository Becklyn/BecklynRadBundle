<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Pagination;

/**
 * Generic pagination calculator.
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
    private $perPage;


    /**
     * @var int
     */
    private $maxPage;


    /**
     * @param int $currentPage
     * @param int $perPage
     * @param int $numberOfItems
     */
    public function __construct (int $currentPage, int $perPage = 50, int $numberOfItems = 0)
    {
        if ($perPage <= 0)
        {
            throw new \InvalidArgumentException("Pagination can only be created for at least 1 item per page.");
        }

        if ($numberOfItems < 0)
        {
            throw new \InvalidArgumentException("Pagination can only be created for a positive number of items");
        }

        $this->currentPage = $currentPage;
        $this->numberOfItems = $numberOfItems;
        $this->perPage = $perPage;
        $this->maxPage = (int) \max(1, (int) \ceil($numberOfItems / $perPage));

        // sanitized current page
        if ($currentPage < 1)
        {
            $this->currentPage = 1;
        }
        elseif ($currentPage > $this->maxPage)
        {
            $this->currentPage = $this->maxPage;
        }
    }



    /**
     * Returns the sanitized current page.
     *
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
     * @return int
     */
    public function getPerPage () : int
    {
        return $this->perPage;
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
            "perPage" => $this->perPage,
        ];
    }


    /**
     * @param int $numberOfItems
     *
     * @return Pagination
     */
    public function withNumberOfItems (int $numberOfItems) : self
    {
        return new self($this->currentPage, $numberOfItems, $this->perPage);
    }
}
