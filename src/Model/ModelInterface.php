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
     *
     * @return $this
     */
    public function add (object $entity);


    /**
     * Updates the given entity.
     *
     * @param object $entity
     *
     * @return $this
     */
    public function update (object $entity);


    /**
     * Marks the entity for removal.
     *
     * @param object $entity
     *
     * @return $this
     */
    public function remove (object $entity);


    /**
     * Flushes the entity changes to the database.
     *
     * @return $this
     */
    public function flush ();
}
