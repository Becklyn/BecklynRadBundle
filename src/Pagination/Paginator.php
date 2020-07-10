<?php declare(strict_types=1);

namespace Becklyn\Rad\Pagination;

use Becklyn\Rad\Pagination\Data\PaginatedList;
use Becklyn\Rad\Pagination\Data\Pagination;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator
{
    /**
     * Returns paginated content.
     *
     * @param QueryBuilder $queryBuilder The query builder to retrieve the entities
     * @param Pagination   $pagination   The pagination object
     */
    public function paginate (QueryBuilder $queryBuilder, Pagination $pagination) : PaginatedList
    {
        $totalNumberOfItems = \count(new DoctrinePaginator($queryBuilder->getQuery()));
        $newPagination = $pagination->withNumberOfItems($totalNumberOfItems);

        if ($totalNumberOfItems > 0)
        {
            $queryBuilder
                ->setFirstResult($newPagination->getOffset())
                ->setMaxResults($pagination->getPerPage());

            $list = \iterator_to_array(new DoctrinePaginator($queryBuilder->getQuery()));
        }
        else
        {
            $list = [];
        }

        return new PaginatedList($list, $newPagination);
    }
}
