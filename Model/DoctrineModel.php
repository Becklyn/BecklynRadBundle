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
        $this->flush();
    }



    /**
     * Removes the given entities
     *
     * @param object[] ...$entities
     */
    protected function removeEntity (...$entities)
    {
        $entities = array_filter($entities);

        if (empty($entities))
        {
            return;
        }

        $entityManager = $this->getEntityManager();
        array_map([$entityManager, "remove"], $entities);
        $entityManager->flush();
    }



    /**
     * Flushes the given entity, only called for adding and updating entities
     *
     * @param object $entity
     *
     * @deprecated use {@see DoctrineModel::flush()} instead
     */
    protected function flushEntity ($entity)
    {
        $this->getEntityManager()->flush();
    }



    /**
     * Flushes all changes
     */
    protected function flush ()
    {
        $this->getEntityManager()->flush();
    }
}
