<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Sortable;

use Becklyn\RadBundle\Entity\Interfaces\SortableEntityInterface;
use Becklyn\RadBundle\Exception\InvalidSortOperationException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class SortableHandler
{
    /**
     * @var EntityRepository
     */
    private $repository;


    /**
     * @param EntityRepository $repository
     */
    public function __construct (EntityRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Returns the next sort order value.
     *
     * @param array $where
     *
     * @return int
     */
    public function getNextSortOrder (array $where = []) : int
    {
        $currentMaxValue = $this->getBaseQuery($where)
            ->select("MAX(t.sortOrder)")
            ->getQuery()
            ->getSingleScalarResult();

        return null !== $currentMaxValue
            ? 1 + (int) $currentMaxValue
            : 0;
    }


    /**
     * Sorts the given $entity before the $before entity.
     * If no $before entity is given, the element will be moved to the end.
     *
     * @param SortableEntityInterface      $entity
     * @param SortableEntityInterface|null $before
     * @param array                        $where
     */
    public function sortElementBefore (SortableEntityInterface $entity, ?SortableEntityInterface $before, array $where = []) : void
    {
        if ($entity === $before)
        {
            throw new InvalidSortOperationException("Can't sort an element before itself.");
        }

        $entities = $this->getBaseQuery($where)
            ->select("t")
            ->addOrderBy("t.sortOrder", "asc")
            ->getQuery()
            ->iterate();

        $index = 0;

        foreach ($entities as $row)
        {
            /** @var SortableEntityInterface $existing */
            $existing = $row[0];

            // skip entity to move, as it will be placed below
            if ($existing === $entity)
            {
                continue;
            }

            // reference element found, move $entity before it
            if ($existing === $before)
            {
                $entity->setSortOrder($index);
                ++$index;
            }

            $existing->setSortOrder($index);
            ++$index;
        }

        // no $before reference element given, it should be moved to the end
        // -> just use next index
        if (null === $before)
        {
            $entity->setSortOrder($index);
        }
    }


    /**
     * Fixes the sort order for all elements.
     *
     * Will fetch all matching elements and resets all sort orders, except for the ones that are given here.
     *
     * @param SortableEntityInterface[] $excludedElements
     * @param array                     $where
     */
    public function fixSortOrder (array $excludedElements, array $where = []) : void
    {
        $entities = $this->getBaseQuery($where)
            ->select("t")
            ->addOrderBy("t.sortOrder", "asc")
            ->getQuery()
            ->iterate();

        $index = 0;

        foreach ($entities as $row)
        {
            /** @var SortableEntityInterface $entity */
            $entity = $row[0];

            if (\in_array($entity, $excludedElements, true))
            {
                continue;
            }

            $entity->setSortOrder($index);
            ++$index;
        }
    }


    /**
     * Builds the base query.
     *
     * @param array $where
     *
     * @return QueryBuilder
     */
    private function getBaseQuery (array $where) : QueryBuilder
    {
        $queryBuilder = $this->repository->createQueryBuilder("t");

        // apply the where clauses
        if (!empty($where))
        {
            $whereCondition = $queryBuilder->expr()->andX();
            $index = 0;

            foreach ($where as $key => $value)
            {
                if (null === $value)
                {
                    $whereCondition->add("t.{$key} IS NULL");
                }
                else
                {
                    $whereCondition->add("t.{$key} = :where_value_{$index}");
                    $queryBuilder->setParameter("where_value_{$index}", $value);
                }

                ++$index;
            }

            $queryBuilder->andWhere($whereCondition);
        }

        return $queryBuilder;
    }
}
