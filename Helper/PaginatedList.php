<?php

namespace OAGM\BaseBundle\Helper;

class PaginatedList
{
    /**
     * The entity list
     *
     * @var array
     */
    private $list;


    /**
     * The pagination
     *
     * @var \OAGM\BaseBundle\Helper\Pagination
     */
    private $pagination;



    /**
     * Constructs a new paginated list
     *
     * @param array $list
     * @param Pagination $pagination
     */
    public function __construct (array $list, Pagination $pagination)
    {
        $this->list = $list;
        $this->pagination = $pagination;
    }



    /**
     * @return array
     */
    public function getList ()
    {
        return $this->list;
    }



    /**
     * @return \OAGM\BaseBundle\Helper\Pagination
     */
    public function getPagination ()
    {
        return $this->pagination;
    }
}