<?php

namespace OAGM\BaseBundle\Model;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use OAGM\BaseBundle\Helper\PaginatedList;
use OAGM\BaseBundle\Helper\Pagination;

/**
 * Base model for database accesses
 */
abstract class DoctrineModel extends ContainerAwareModel
{
    /**
     * Returns the repository
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository ()
    {
        return $this->get('doctrine')->getRepository($this->getFullEntityName());
    }



    /**
     * Returns the entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager ()
    {
        return $this->get('doctrine')->getManager();
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
     * Returns paginated content
     *
     * If the $queryBuilder for entities does include a grouping function, the automatic counting will fail (since
     * sub selects are not fully supported in DQL). You can pass an additional query builder just for the counting query
     * in $countQueryBuilder then (no need to set the select("COUNT(..)"), as this will be done automatically).
     *
     * @param QueryBuilder $queryBuilder The query builder to retrieve the entities
     * @param Pagination $pagination The pagination object
     * @param QueryBuilder|null $countQueryBuilder The additional query builder, just for the count query
     *
     * @return PaginatedList
     */
    protected function getPaginatedResults (QueryBuilder $queryBuilder, Pagination $pagination, QueryBuilder $countQueryBuilder = null)
    {
        $pagination->setNumberOfItems($this->getTotalNumberOfItems($queryBuilder, $countQueryBuilder));
        $offset = ($pagination->getCurrentPage() - $pagination->getMinPage()) * $pagination->getItemsPerPage();

        if (0 < $pagination->getNumberOfItems())
        {
            $queryBuilder
                ->setFirstResult($offset)
                ->setMaxResults($pagination->getItemsPerPage());

            $list = iterator_to_array(new Paginator($queryBuilder->getQuery()));
        }
        else
        {
            $list = array();
        }

        return new PaginatedList(
            $list,
            $pagination
        );
    }



    /**
     * Returns the number of total items in a query builder query
     *
     * @param QueryBuilder $queryBuilder
     * @param QueryBuilder $countQueryBuilder
     *
     * @return int
     */
    private function getTotalNumberOfItems (QueryBuilder $queryBuilder, QueryBuilder $countQueryBuilder = null)
    {
        $queryBuilder = clone ($countQueryBuilder ?: $queryBuilder);
        $table = current($queryBuilder->getRootAliases());

        try {
            return (int) $queryBuilder->select("COUNT({$table})")
                ->getQuery()
                ->getSingleScalarResult();
        }
        catch (NoResultException $e)
        {
            return 0;
        }
    }



    /**
     * Returns the entity name
     *
     * @abstract
     * @return string
     */
    abstract protected function getFullEntityName ();
}