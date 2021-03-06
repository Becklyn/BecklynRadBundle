<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Entity\Interfaces;

interface EntityInterface
{
    /**
     * Returns the ID of the entity.
     * May only return `null` for unstored entities.
     */
    public function getId () : ?int;
}
