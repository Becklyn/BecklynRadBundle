<?php declare(strict_types=1);

namespace Becklyn\Rad\Pagination\Data;

use Becklyn\Rad\Exception\InvalidPaginationException;

/**
 * Generic pagination calculator.
 */
class Pagination
{
    /**
     * WARNING: this value is unsanitized. You should never use it except for when passing it to a pagination with a
     * different number of elements. Always use the getter, which returns the normalized value. Also use the normalized
     * getter in internal methods to have correct calculation.
     */
    private int $currentPage;
    private int $numberOfItems;
    private int $perPage;
    private int $maxPage;


    /**
     */
    public function __construct (int $currentPage, int $perPage = 50, int $numberOfItems = 0)
    {
        if ($perPage <= 0)
        {
            throw new InvalidPaginationException("Pagination can only be created for at least 1 item per page.");
        }

        if ($numberOfItems < 0)
        {
            throw new InvalidPaginationException("Pagination can only be created for a positive number of items");
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
        $current = $this->getCurrentPage();

        return $current < $this->maxPage
            ? $current + 1
            : null;
    }


    /**
     */
    public function getPreviousPage () : ?int
    {
        $current = $this->getCurrentPage();

        return $current > 1
            ? $current - 1
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
    public function getOffset () : int
    {
        return ($this->getCurrentPage() - $this->getMinPage()) * $this->getPerPage();
    }


    /**
     */
    public function toArray () : array
    {
        return [
            "current" => $this->getCurrentPage(),
            "min" => $this->getMinPage(),
            "max" => $this->maxPage,
            "next" => $this->getNextPage(),
            "prev" => $this->getPreviousPage(),
            "perPage" => $this->perPage,
            "total" => $this->numberOfItems,
            "valid" => $this->isValid(),
        ];
    }


    /**
     * @return Pagination
     */
    public function withNumberOfItems (int $numberOfItems) : self
    {
        return new self($this->currentPage, $this->perPage, $numberOfItems);
    }


    /**
     * Returns whether the current configuration is valid (ie. whether the set current page is valid within
     * the given min / max page range).
     */
    public function isValid () : bool
    {
        return $this->currentPage === $this->getCurrentPage();
    }
}
