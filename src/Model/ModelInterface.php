<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Model;

/**
 * Interface for base class for all models.
 */
interface ModelInterface
{
    /**
     * Marks the entity for adding.
     *
     * @param object $entity
     */
    public function add (object $entity) : void;


    /**
     * Updates the given entity.
     *
     * @param object $entity
     */
    public function update (object $entity) : void;


    /**
     * Marks the entity for removal.
     *
     * @param object $entity
     */
    public function remove (object $entity) : void;


    /**
     * Flushes the entity changes to the database.
     */
    public function flush () : void;
}
