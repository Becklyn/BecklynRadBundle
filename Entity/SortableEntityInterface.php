<?php

namespace Becklyn\RadBundle\Entity;


/**
 *
 */
interface SortableEntityInterface extends IdEntityInterface
{
    /**
     * Returns the sort order
     *
     * @return int
     */
    public function getSortOrder () : int;



    /**
     * Sets the sort order
     *
     * @param int $sortOrder
     */
    public function setSortOrder (int $sortOrder);
}
