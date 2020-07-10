<?php declare(strict_types=1);

namespace Becklyn\Rad\Entity\Interfaces;

interface EntityInterface
{
    /**
     * Returns the ID of the entity.
     * May only return `null` for unstored entities.
     */
    public function getId () : ?int;


    /**
     * Returns whether this entity was already persisted and flushed (`false`) or if it is new (`true`).
     *
     * @return bool true if not yet flushed, false otherwise
     */
    public function isNew () : bool;
}
