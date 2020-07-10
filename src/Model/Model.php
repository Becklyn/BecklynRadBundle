<?php declare(strict_types=1);

namespace Becklyn\Rad\Model;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Base class for all models.
 */
abstract class Model implements ModelInterface
{
    protected EntityManagerInterface $entityManager;


    /**
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
    public function add (object $entity)
    {
        $this->entityManager->persist($entity);
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function update (object $entity)
    {
        if (\method_exists($entity, 'markAsModified'))
        {
            $entity->markAsModified();
        }

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function remove (object $entity)
    {
        $this->entityManager->remove($entity);
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function flush ()
    {
        $this->entityManager->flush();
        return $this;
    }
}
