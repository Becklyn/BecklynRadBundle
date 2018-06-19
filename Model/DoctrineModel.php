<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Model;

use Becklyn\RadBundle\Exception\AutoConfigurationFailedException;
use Becklyn\RadBundle\Exception\EntityRemovalBlockedException;
use Becklyn\RadBundle\Helper\ClassNameTransformer;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
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
        $this->doctrine = $doctrine;
        $this->classNameTransformer = new ClassNameTransformer();
    }


    /**
     * Returns the repository
     *
     * @param null|string $persistentObject you can specify which repository you want to load. Defaults to the
     *                                      automatically derived one
     *
     * @return ObjectRepository|EntityRepository
     */
    protected function getRepository ($persistentObject = null) : EntityRepository
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
    protected function getEntityManager () : EntityManager
    {
        return $this->doctrine->getEntityManager();
    }


    /**
     * Returns, whether it is a valid id
     *
     * @param int $id
     *
     * @return bool
     */
    protected function isId ($id) : bool
    {
        return is_int($id) || ctype_digit($id);
    }


    /**
     * Returns the entity name
     *
     * @return string the entity reference string
     * @throws AutoConfigurationFailedException
     */
    protected function getFullEntityName () : string
    {
        $modelClassName = get_class($this);
        $entityClass = $this->classNameTransformer->transformModelToEntity($modelClassName);

        if (!class_exists($entityClass))
        {
            throw new AutoConfigurationFailedException(sprintf(
                "Cannot automatically generate entity name for model '%s', guessed '%s'.",
                $modelClassName,
                $entityClass
            ));
        }

        return $entityClass;
    }


    /**
     * Adds the given entity
     *
     * @param object $entity
     */
    protected function addEntity ($entity) : void
    {
        $this->getEntityManager()->persist($entity);
        $this->flush();
    }


    /**
     * Removes the given entities
     *
     * @param object[] ...$entities
     */
    protected function removeEntity (...$entities) : void
    {
        try
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
        catch (ForeignKeyConstraintViolationException $foreignKeyException)
        {
            throw new EntityRemovalBlockedException(
                $entities,
                "Can't remove entities as a foreign key constraint failed.",
                $foreignKeyException
            );
        }
    }


    /**
     * Flushes all changes
     */
    protected function flush () : void
    {
        $this->getEntityManager()->flush();
    }
}
