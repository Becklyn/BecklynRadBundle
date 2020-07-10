<?php declare(strict_types=1);

namespace Becklyn\Rad\Sortable;

use Becklyn\Rad\Entity\Interfaces\SortableEntityInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * A sortable handler, that fetches the `$where` attribute of the regular sortable handler automatically from the given
 * entity.
 */
final class PropertiesSortableHandler
{
    private EntityRepository $repository;
    private SortableHandler $nested;
    /** @var string[] */
    private array $properties;
    private PropertyAccessor $accessor;


    /**
     * @param string ...$properties The properties that should be used for generating the `$where` sortable filter array.
     */
    public function __construct (EntityRepository $repository, string ...$properties)
    {
        $this->repository = $repository;
        $this->nested = new SortableHandler($repository);
        $this->properties = $properties;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }


    /**
     * Sets the next sort order value on the given entity
     */
    public function setNextSortOrder (SortableEntityInterface $entity) : void
    {
        $entity->setSortOrder(
            $this->nested->getNextSortOrder($this->buildWhere($entity))
        );
    }


    /**
     * Fixes the sort order for all elements.
     *
     * Will fetch all matching elements and resets all sort orders, except for the ones that are given here.
     */
    public function fixSortOrder (SortableEntityInterface $removedEntity) : void
    {
        $this->nested->fixSortOrder([$removedEntity], $this->buildWhere($removedEntity));
    }


    /**
     * Sorts the given $entity before the $before entity.
     * If no $before entity is given, the element will be moved to the end.
     *
     * @return bool whether the sort order could be adjusted correctly.
     */
    public function sortElementBefore (SortableEntityInterface $entity, ?SortableEntityInterface $before) : bool
    {
        return $this->areCompatible($entity, $before)
            ? $this->nested->sortElementBefore($entity, $before, $this->buildWhere($entity))
            : false;
    }


    /**
     * Builds the where filter from the given entity
     */
    private function buildWhere (SortableEntityInterface $entity) : array
    {
        $where = [];

        foreach ($this->properties as $property)
        {
            $where[$property] = $this->accessor->getValue($entity, $property);
        }

        return $where;
    }


    /**
     * Returns whether the two given sortables are compatible
     */
    public function areCompatible (SortableEntityInterface $left, ?SortableEntityInterface $right) : bool
    {
        if (null === $right)
        {
            return true;
        }

        if ($left === $right)
        {
            return false;
        }

        foreach ($this->properties as $property)
        {
            $leftValue = $this->accessor->getValue($left, $property);
            $rightValue = $this->accessor->getValue($right, $property);

            if ($leftValue !== $rightValue)
            {
                return false;
            }
        }

        return true;
    }
}
