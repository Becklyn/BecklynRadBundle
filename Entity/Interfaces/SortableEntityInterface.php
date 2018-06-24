<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Entity\Interfaces;


interface SortableEntityInterface extends EntityInterface
{
    /**
     * Returns the sort order
     *
     * @return int
     */
    public function getSortOrder () : ?int;


    /**
     * Sets the sort order
     *
     * @param int $sortOrder
     */
    public function setSortOrder (int $sortOrder) : void;
}
