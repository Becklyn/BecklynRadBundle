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
     * @param QueryBuilder $queryBuilder
     * @param Pagination $pagination
     *
     * @return PaginatedList
     */
    protected function getPaginatedResults (QueryBuilder $queryBuilder, Pagination $pagination)
    {
        $pagination->setNumberOfItems($this->getTotalNumberOfItems($queryBuilder));
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
     *
     * @return int
     */
    private function getTotalNumberOfItems (QueryBuilder $queryBuilder)
    {
        $queryBuilder = clone $queryBuilder;
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