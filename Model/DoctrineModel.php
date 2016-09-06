<?php

namespace Becklyn\RadBundle\Model;

use Becklyn\RadBundle\Helper\ClassNameTransformer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * Base model for database accesses
 */
abstract class DoctrineModel
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;


    /**
     * @var ClassNameTransformer
     */
    private $classNameTransformer;



    /**
     * @param RegistryInterface $doctrine
     */
    public function __construct (RegistryInterface $doctrine)
    {
        $this->doctrine             = $doctrine;
        $this->classNameTransformer = new ClassNameTransformer();
    }



    /**
     * Returns the repository
     *
     * @param null|string $persistentObject you can specify which repository you want to load. Defaults to the automatically derived one
     *
     * @return EntityRepository
     * @throws \Exception
     */
    protected function getRepository ($persistentObject = null)
    {
        if (null === $persistentObject)
        {
            $persistentObject = $this->getFullEntityName();
        }

        return $this->doctrine->getRepository($persistentObject);
    }



    /**
     * Returns the entity manager
     *
     * @return EntityManager
     */
    protected function getEntityManager ()
    {
        return $this->doctrine->getManager();
    }



    /**
     * Returns, whether it is a valid id
     *
     * @param int $id
     *
     * @return bool
     */
    protected function isId ($id)
    {
        return is_int($id) || ctype_digit($id);
    }



    /**
     * Returns the entity name
     *
     * @throws \Exception if the full entity name could not be guessed automatically
     * @return string the entity reference string
     */
    protected function getFullEntityName ()
    {
        $modelClassName = get_class($this);
        $entityClass    = $this->classNameTransformer->transformModelToEntity($modelClassName);

        if (!class_exists($entityClass))
        {
            throw new \Exception(
                sprintf("Cannot automatically generate entity name for model '%s', guessed '%s'.", $modelClassName, $entityClass)
            );
        }

        return $entityClass;
    }



    /**
     * Adds the given entity
     *
     * @param object $entity
     */
    protected function addEntity ($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->flushEntity($entity);
    }



    /**
     * Removes the given entity
     *
     * @param object $entity
     */
    protected function removeEntity ($entity)
    {
        // skip if the item was already removed
        if (null === $entity)
        {
            return;
        }

        if (is_array($entity))
        {
            foreach ($entity as $singleEntity)
            {
                $this->removeEntity($singleEntity);
            }

            return;
        }

        $entityManager = $this->getEntityManager();
        $entityManager->remove($entity);
        $entityManager->flush($entity);
    }



    /**
     * Flushes the given entity, only called for adding and updating entities
     *
     * @param object $entity
     */
    protected function flushEntity ($entity)
    {
        $this->getEntityManager()->flush($entity);
    }
}
