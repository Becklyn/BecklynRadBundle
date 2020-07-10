<?php declare(strict_types=1);

namespace Tests\Becklyn\Rad\Sortable;

use Becklyn\Rad\Entity\Interfaces\SortableEntityInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

trait SortableTestTrait
{
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


            public function isNew () : bool
            {
                return false;
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
     * @param int      $id
     * @param int|null $sortOrder
     *
     * @return SortableEntityInterface
     */
    private function createEntityWithProperties (int $id, $a, $b, $c, ?int $sortOrder = null) : SortableEntityInterface
    {
        return new class ($id, $sortOrder, $a, $b, $c) implements SortableEntityInterface
        {
            private $id;
            private $sortOrder;
            private $a;
            private $b;
            private $c;

            public function __construct (int $id, ?int $sortOrder, $a, $b, $c)
            {
                $this->id = $id;
                $this->sortOrder = $sortOrder;
                $this->a = $a;
                $this->b = $b;
                $this->c = $c;
            }


            public function getId () : ?int
            {
                return $this->id;
            }


            public function isNew () : bool
            {
                return null !== $this->id;
            }


            public function getSortOrder () : ?int
            {
                return $this->sortOrder;
            }


            public function setSortOrder (int $sortOrder) : void
            {
                $this->sortOrder = $sortOrder;
            }

            public function getA ()
            {
                return $this->a;
            }

            public function getB ()
            {
                return $this->b;
            }

            public function getC ()
            {
                return $this->c;
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


    /**
     */
    private function createIteratingRepository (SortableEntityInterface ...$entities) : array
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
            ->method("expr")
            ->willReturn(new Expr());

        $queryBuilder
            ->method("getQuery")
            ->willReturn($query);

        \usort(
            $entities,
            function (SortableEntityInterface $left, SortableEntityInterface $right)
            {
                return $left->getSortOrder() - $right->getSortOrder();
            }
        );

        $result = [];
        foreach ($entities as $entity)
        {
            $result[] = [$entity];
        }

        $query
            ->method("iterate")
            ->willReturn($result);

        return [$repository, $queryBuilder];
    }
}
