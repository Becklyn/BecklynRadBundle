<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Model;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Base class for all models.
 */
abstract class Model
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;


    /**
     * @param RegistryInterface $registry
     */
    public function __construct (RegistryInterface $registry)
    {
        $this->entityManager = $registry->getEntityManager();
    }


    /**
     * Marks the entity for adding.
     *
     * @param object $entity
     */
    public function add (object $entity) : void
    {
        $this->entityManager->persist($entity);
    }


    /**
     * Updates the given entity.
     *
     * @param object $entity
     */
    public function update (object $entity) : void
    {
        if (\method_exists($entity, 'markAsModified'))
        {
            $entity->markAsModified();
        }
    }


    /**
     * Marks the entity for removal.
     *
     * @param object $entity
     */
    public function remove (object $entity) : void
    {
        $this->entityManager->remove($entity);
    }


    /**
     * Flushes the entity changes to the database.
     */
    public function flush () : void
    {
        $this->entityManager->flush();
    }
}
