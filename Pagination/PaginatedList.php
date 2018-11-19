<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Pagination;


/**
 * Generic value object for returning paginated lists
 */
class PaginatedList
{
    /**
     * @var array
     */
    private $list;


    /**
     * @var Pagination
     */
    private $pagination;


    public function __construct (array $list, Pagination $pagination)
    {
        $this->list = $list;
        $this->pagination = $pagination;
    }


    /**
     * @return array
     */
    public function getList () : array
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
