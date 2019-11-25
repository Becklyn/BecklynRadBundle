<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Pagination\Data;

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
    }



    /**
     * Returns the sanitized current page.
     */
    public function getCurrentPage () : int
    {
        // sanitized current page
        if ($this->currentPage < 1)
        {
            return 1;
        }

        if ($this->currentPage > $this->maxPage)
        {
            return $this->maxPage;
        }

        return $this->currentPage;
    }


    /**
     */
    public function getMinPage () : int
    {
        return 1;
    }


    /**
     */
    public function getMaxPage () : int
    {
        return $this->maxPage;
    }


    /**
     */
    public function getPerPage () : int
    {
        return $this->perPage;
    }


    /**
     */
    public function getNextPage () : ?int
    {
        return $this->currentPage < $this->maxPage
            ? $this->currentPage + 1
            : null;
    }


    /**
     */
    public function getPreviousPage () : ?int
    {
        return $this->currentPage > 1
            ? $this->currentPage - 1
            : null;
    }


    /**
     */
    public function getNumberOfItems () : int
    {
        return $this->numberOfItems;
    }


    /**
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
     * @return Pagination
     */
    public function withNumberOfItems (int $numberOfItems) : self
    {
        return new self($this->currentPage, $this->perPage, $numberOfItems);
    }
}
