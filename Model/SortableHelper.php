<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Model;

use Becklyn\RadBundle\Entity\Interfaces\SortableEntityInterface;
use Doctrine\ORM\EntityRepository;


class SortableHelper
{
    /**
     * @var EntityRepository
     */
    private $repository;


    /**
     * @var array
     */
    private $globalWhere;



    /**
     * @param EntityRepository $repository
     * @param array $globalWhere
     */
    public function __construct (EntityRepository $repository, array $globalWhere = array())
    {
        $this->repository = $repository;
        $this->globalWhere = $globalWhere;
    }



    /**
     * Returns the next sort order value
     *
     * @param array $where
     *
     * @return int
     */
    public function getNextSortOrder (array $where = array()) : int
    {
        $queryBuild = $this->repository->createQueryBuilder("t")
            ->select("MAX(t.sortOrder)");

        // apply the where clauses
        if (0 < count($completeWhere = $this->getCompleteWhere($where)))
        {
            $queryParts = array();
            $index = 0;

            foreach ($completeWhere as $key => $value)
            {
                if (null === $value)
                {
                    $queryParts[] = "t.{$key} IS NULL";
                }
                else
                {
                    $queryParts[] = "t.{$key} = :where_value_{$index}";
                    $queryBuild->setParameter("where_value_{$index}", $value);
                }

                $index++;
            }

            $queryBuild->where(implode(" AND ", $queryParts));
        }

        $currentMaxValue = $queryBuild
            ->getQuery()
            ->getSingleScalarResult();

        return null !== $currentMaxValue
            ? 1 + (int) $currentMaxValue
            : 0;
    }


    /**
     * Returns all entities in the correct order
     *
     * @param array $where
     *
     * @return SortableEntityInterface[]
     */
    private function getAllEntities (array $where = array()) : array
    {
        return $this->repository->findBy($this->getCompleteWhere($where), array("sortOrder" => "asc"));
    }



    /**
     * Fixes the sort ordering
     */
    public function fixSortOrdering (array $where = array()) : void
    {
        $this->prependEntities(array(), $where);
    }



    /**
     * Prepends the given entities to the list
     *
     * @param SortableEntityInterface[] $prepended
     * @param array                     $where
     */
    public function prependEntities (array $prepended, array $where = array()) : void
    {
        $all = $this->getAllEntities($where);
        $index = 0;

        // first prepend
        foreach ($prepended as $prependedEntity)
        {
            $prependedEntity->setSortOrder($index);
            $index++;
        }

        // then sort the rest
        foreach ($all as $entity)
        {
            if (in_array($entity, $prepended, true))
            {
                continue;
            }

            $entity->setSortOrder($index);
            $index++;
        }
    }



    /**
     * Applies the sorting
     *
     * Sort Mapping:
     *  entity-id => position (0-based)
     *
     * @param array $sortMapping
     * @param array $where
     *
     * @return bool
     */
    public function applySorting ($sortMapping, array $where = array()) : bool
    {
        if (!is_array($sortMapping))
        {
            // if value is not in correct format
            return false;
        }

        $all = $this->getAllEntities($where);

        // check sort values
        $possibleSortValues = range(0, count($all) - 1);
        if (!$this->arraysAreIdentical($possibleSortValues, array_values($sortMapping)))
        {
            // the given sort values are wrong
            return false;
        }

        // check item ids
        $allIds = array_map(function (SortableEntityInterface $entity) { return $entity->getId(); }, $all);
        if (!$this->arraysAreIdentical($allIds, array_keys($sortMapping)))
        {
            // the given item ids are wrong
            return false;
        }

        foreach ($all as $entity)
        {
            $entity->setSortOrder($sortMapping[$entity->getId()]);
        }

        return true;
    }



    /**
     * Returns the complete where
     *
     * @param array $where
     *
     * @return array
     */
    private function getCompleteWhere (array $where) : array
    {
        return \array_replace($this->globalWhere, $where);
    }



    /**
     * Returns whether two arrays are identical
     *
     * @param array $array1
     * @param array $array2
     *
     * @return bool
     */
    private function arraysAreIdentical (array $array1, array $array2) : bool
    {
        return (0 === count(array_diff($array1, $array2)))
            && (0 === count(array_diff($array2, $array1)));
    }
}
