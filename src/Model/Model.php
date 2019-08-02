<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Base class for all models.
 */
abstract class Model implements ModelInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;


    /**
     * @param ManagerRegistry $registry
     */
    public function __construct (ManagerRegistry $registry)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $registry->getManager();
        $this->entityManager = $entityManager;
    }


    /**
     * @inheritDoc
     */
    public function add (object $entity) : void
    {
        $this->entityManager->persist($entity);
    }


    /**
     * @inheritDoc
     */
    public function update (object $entity) : void
    {
        if (\method_exists($entity, 'markAsModified'))
        {
            $entity->markAsModified();
        }
    }


    /**
     * @inheritDoc
     */
    public function remove (object $entity) : void
    {
        $this->entityManager->remove($entity);
    }


    /**
     * @inheritDoc
     */
    public function flush () : void
    {
        $this->entityManager->flush();
    }
}
