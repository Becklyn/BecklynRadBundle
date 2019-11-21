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
    public function add (object $entity) : ModelInterface
    {
        $this->entityManager->persist($entity);
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function update (object $entity) : ModelInterface
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
    public function remove (object $entity) : ModelInterface
    {
        $this->entityManager->remove($entity);
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function flush () : ModelInterface
    {
        $this->entityManager->flush();
        return $this;
    }
}
