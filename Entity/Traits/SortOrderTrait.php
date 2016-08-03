<?php

namespace Becklyn\RadBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 */
trait SortOrderTrait
{
    /**
     * @var int
     * @ORM\Column(name="sort_order", type="integer")
     */
    private $sortOrder;



    /**
     * @return int
     */
    public function getSortOrder () : int
    {
        return $this->sortOrder;
    }



    /**
     * @param int $sortOrder
     */
    public function setSortOrder (int $sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }
}
