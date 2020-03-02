<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Sortable;

use Becklyn\RadBundle\Sortable\PropertiesSortableHandler;
use PHPUnit\Framework\TestCase;

class PropertiesSortableHandlerTest extends TestCase
{
    use SortableTestTrait;

    //region setNextSortOrder() Tests
    public function testNextSortOrderFull () : void
    {
        $entity = $this->createEntityWithProperties(5, "a", "b", "c");
        [$repository, $queryBuilder] = $this->createIteratingRepository();

        $queryBuilder
            ->expects(self::exactly(3))
            ->method("setParameter")
            ->withConsecutive(
                ["where_value_0", "a"],
                ["where_value_1", "b"],
                ["where_value_2", "c"]
            );

        $sortable = new PropertiesSortableHandler($repository, "a", "b", "c");
        $sortable->setNextSortOrder($entity);
    }

    public function testNextSortOrderPartial () : void
    {
        $entity = $this->createEntityWithProperties(5, "a", "b", "c");
        [$repository, $queryBuilder] = $this->createIteratingRepository();

        $queryBuilder
            ->expects(self::exactly(1))
            ->method("setParameter")
            ->withConsecutive(
                ["where_value_0", "c"]
            );

        $sortable = new PropertiesSortableHandler($repository,  "c");
        $sortable->setNextSortOrder($entity);
    }

    public function testNextSortOrderEmpty () : void
    {
        $entity = $this->createEntityWithProperties(5, "a", "b", "c");
        [$repository, $queryBuilder] = $this->createIteratingRepository();

        $queryBuilder
            ->expects(self::exactly(0))
            ->method("setParameter");

        $sortable = new PropertiesSortableHandler($repository);
        $sortable->setNextSortOrder($entity);
    }
    //endregion


    //region sortElementBefore() Tests
    public function testSortElementBeforeValidBothSet () : void
    {
        $first = $this->createEntityWithProperties(1, "a", "b", "c", 0);
        $before = $this->createEntityWithProperties(3, "a", "b", "c", 1);
        $entity = $this->createEntityWithProperties(2, "a", "b", "c");
        [$repository] = $this->createIteratingRepository($entity, $before, $first);

        $sortable = new PropertiesSortableHandler($repository, "a", "b", "c");
        self::assertTrue($sortable->sortElementBefore($entity, $before));
        self::assertSame(0, $first->getSortOrder());
        self::assertSame(1, $entity->getSortOrder());
        self::assertSame(2, $before->getSortOrder());
    }


    public function testSortElementBeforeValidWithNull () : void
    {
        $first = $this->createEntityWithProperties(1, "a", "b", "c", 0);
        $entity = $this->createEntityWithProperties(2, "a", "b", "c");
        [$repository] = $this->createIteratingRepository($entity, $first);

        $sortable = new PropertiesSortableHandler($repository, "a", "b", "c");
        self::assertTrue($sortable->sortElementBefore($entity, null));
        self::assertSame(0, $first->getSortOrder());
        self::assertSame(1, $entity->getSortOrder());
    }


    public function testSortElementBeforeInvalid () : void
    {
        $entity = $this->createEntityWithProperties(2, "a", "b", "c");
        $before = $this->createEntityWithProperties(3, "a1", "b", "c");
        [$repository] = $this->createIteratingRepository($entity, $before);

        $sortable = new PropertiesSortableHandler($repository, "a");
        self::assertFalse($sortable->sortElementBefore($entity, $before));
    }
    //endregion
}
