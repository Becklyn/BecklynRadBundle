<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Sortable;

use Becklyn\RadBundle\Entity\Interfaces\SortableEntityInterface;
use Becklyn\RadBundle\Exception\InvalidSortOperationException;
use Becklyn\RadBundle\Sortable\SortableHandler;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SortableHandlerTest extends TestCase
{
    //region Next Sort Order
    /**
     * @return array
     */
    public function provideNextSortOrder () : array
    {
        return [
            [null, 0],
            [0, 1],
            [10, 11],
        ];
    }


    /**
     * @dataProvider provideNextSortOrder
     *
     * @param int|null $returnValue
     * @param int      $expected
     */
    public function testNextSortOrder (?int $returnValue, int $expected) : void
    {
        $repository = $this->createMock(EntityRepository::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $query = $this->createMock(AbstractQuery::class);

        $repository
            ->method("createQueryBuilder")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("select")
            ->willReturnSelf();

        $queryBuilder
            ->method("getQuery")
            ->willReturn($query);

        $query
            ->method("getSingleScalarResult")
            ->willReturn($returnValue);

        $sortable = new SortableHandler($repository);

        self::assertSame($expected, $sortable->getNextSortOrder());
    }
    //endregion


    //region Fix Sort Order
    /**
     *
     */
    public function testFixSortOrder () : void
    {
        $entity1 = $this->createEntity(1, 1);
        $entity2 = $this->createEntity(2, 2);
        $entity3 = $this->createEntity(3, 3);
        $entity4 = $this->createEntity(4, 4);

        $repository = $this->createIteratingRepository($entity1, $entity2, $entity3, $entity4);
        $sortable = new SortableHandler($repository);
        $sortable->fixSortOrder([$entity3]);

        self::assertSame(0, $entity1->getSortOrder());
        self::assertSame(1, $entity2->getSortOrder());
        self::assertSame(2, $entity4->getSortOrder());

        // unchanged
        self::assertSame(3, $entity3->getSortOrder());
    }


    /**
     *
     */
    public function testFixSortOrderExcludedNeverCalled () : void
    {
        $entity1 = $this->createEntity(1, 1);
        $entity2 = $this->createEntity(2, 2);
        $entity3 = $this->createEntity(3, 3);

        $entityExcluded1 = $this->createMock(SortableEntityInterface::class);
        $entityExcluded2 = $this->createMock(SortableEntityInterface::class);
        $entityExcluded1
            ->expects(self::never())
            ->method("setSortOrder");
        $entityExcluded2
            ->expects(self::never())
            ->method("setSortOrder");

        $repository = $this->createIteratingRepository($entity1, $entity2, $entity3, $entityExcluded1, $entityExcluded2);
        $sortable = new SortableHandler($repository);
        $sortable->fixSortOrder([$entityExcluded1, $entityExcluded2]);
    }
    //endregion


    //region Sort Element Before
    /**
     * Tests sorting an element at the beginning
     */
    public function testSortElementBeforeBeginning () : void
    {
        $entities = $this->createEntities(5);
        $repository = $this->createIteratingRepository(...$entities);
        $sortable = new SortableHandler($repository);

        $sortable->sortElementBefore($entities[4], $entities[0]);
        self::assertEquals(
            [
                4 => 0,
                0 => 1,
                1 => 2,
                2 => 3,
                3 => 4,
            ],
            $this->mapEntities($entities)
        );
    }


    /**
     * Tests sorting an element in the middle
     */
    public function testSortElementBeforeMiddle () : void
    {
        $entities = $this->createEntities(5);
        $repository = $this->createIteratingRepository(...$entities);
        $sortable = new SortableHandler($repository);

        $sortable->sortElementBefore($entities[4], $entities[2]);
        self::assertEquals(
            [
                0 => 0,
                1 => 1,
                4 => 2,
                2 => 3,
                3 => 4,
            ],
            $this->mapEntities($entities)
        );
    }


    /**
     * Tests sorting an element to the end
     */
    public function testSortElementToEnd () : void
    {
        $entities = $this->createEntities(5);
        $repository = $this->createIteratingRepository(...$entities);
        $sortable = new SortableHandler($repository);

        $sortable->sortElementBefore($entities[3], null);
        self::assertEquals(
            [
                0 => 0,
                1 => 1,
                2 => 2,
                4 => 3,
                3 => 4,
            ],
            $this->mapEntities($entities)
        );
    }


    /**
     * Ensures that sorting an element before itself isn't supported
     */
    public function testSortElementBeforeSelf () : void
    {
        $this->expectException(InvalidSortOperationException::class);
        $this->expectExceptionMessage("Can't sort an element before itself.");

        $entity = $this->createEntity(1, 1);
        $repository = $this->createIteratingRepository($entity);
        $sortable = new SortableHandler($repository);

        $sortable->sortElementBefore($entity, $entity);
    }
    //endregion


    //region Where Passing
    /**
     * Tests that the `where` parameter are properly passed
     */
    public function testWherePassing () : void
    {
        $repository = $this->createMock(EntityRepository::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $query = $this->createMock(AbstractQuery::class);

        $object = new \stdClass();

        $repository
            ->expects(self::once())
            ->method("createQueryBuilder")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects(self::once())
            ->method("select")
            ->willReturnSelf();

        $queryBuilder
            ->expects(self::once())
            ->method("getQuery")
            ->willReturn($query);

        $query
            ->expects(self::once())
            ->method("getSingleScalarResult")
            ->willReturn(0);

        $expr = $this->createMock(Expr::class);

        $queryBuilder
            ->expects(self::once())
            ->method("expr")
            ->willReturn($expr);

        $andX = new Expr\Andx();

        $expr
            ->expects(self::once())
            ->method("andX")
            ->willReturn($andX);

        $queryBuilder
            ->expects(self::once())
            ->method("andWhere")
            ->with($andX);

        $queryBuilder
            ->expects(self::exactly(2))
            ->method("setParameter")
            ->withConsecutive(["where_value_0", 1], ["where_value_2", $object]);

        $sortable = new SortableHandler($repository);
        $sortable->getNextSortOrder(["a" => 1, "null" => null, "obj" => $object]);

        self::assertSame([
            "t.a = :where_value_0",
            "t.null IS NULL",
            "t.obj = :where_value_2",
        ], $andX->getParts());
    }
    //endregion


    /**
     * @param mixed ...$entities
     *
     * @return MockObject|EntityRepository
     */
    private function createIteratingRepository (...$entities) : MockObject
    {
        $repository = $this->createMock(EntityRepository::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $query = $this->createMock(AbstractQuery::class);

        $repository
            ->method("createQueryBuilder")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("select")
            ->willReturnSelf();

        $queryBuilder
            ->method("addOrderBy")
            ->willReturnSelf();

        $queryBuilder
            ->method("getQuery")
            ->willReturn($query);

        $result = [];
        foreach ($entities as $entity)
        {
            $result[] = [$entity];
        }

        $query
            ->method("iterate")
            ->willReturn($result);

        return $repository;
    }


    /**
     * @param int      $id
     * @param int|null $sortOrder
     *
     * @return SortableEntityInterface
     */
    private function createEntity (int $id, ?int $sortOrder = null) : SortableEntityInterface
    {
        return new class ($id, $sortOrder) implements SortableEntityInterface
        {
            private $id;
            private $sortOrder;

            public function __construct (int $id, ?int $sortOrder = null)
            {
                $this->id = $id;
                $this->sortOrder = $sortOrder;
            }


            public function getId () : ?int
            {
                return $this->id;
            }


            public function getSortOrder () : ?int
            {
                return $this->sortOrder;
            }


            public function setSortOrder (int $sortOrder) : void
            {
                $this->sortOrder = $sortOrder;
            }
        };
    }


    /**
     * @param int $number
     *
     * @return array
     */
    private function createEntities (int $number) : array
    {
        $result = [];

        for ($i = 0; $i < $number; $i++)
        {
            $result[] = $this->createEntity($i, $i);
        }

        return $result;
    }


    /**
     * @param SortableEntityInterface[] $entities
     *
     * @return array
     */
    private function mapEntities (array $entities) : array
    {
        $result = [];

        foreach ($entities as $entity)
        {
            $result[$entity->getId()] = $entity->getSortOrder();
        }

        return $result;
    }
}
