<?php

namespace OAGM\BaseBundle\Model;

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
        return $this->get('doctrine')->getEntityManager();
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
     * Returns the entity name
     *
     * @abstract
     * @return string
     */
    abstract protected function getFullEntityName ();
}