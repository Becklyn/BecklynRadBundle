<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Pagination;


/**
 * Generic value object for returning paginated lists
 */
class PaginatedList
{
    /**
     * @var iterable
     */
    private $list;


    /**
     * @var Pagination
     */
    private $pagination;


    public function __construct (iterable $list, Pagination $pagination)
    {
        $this->list = $list;
        $this->pagination = $pagination;
    }


    /**
     * @return iterable
     */
    public function getList () : iterable
    {
        return $this->list;
    }


    /**
     * @return Pagination
     */
    public function getPagination () : Pagination
    {
        return $this->pagination;
    }
}
