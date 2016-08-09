<?php

namespace Becklyn\RadBundle\Entity;

interface SortableEntity extends IdEntity
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
