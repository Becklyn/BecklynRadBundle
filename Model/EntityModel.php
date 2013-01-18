<?php

namespace OAGM\BaseBundle\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for models containing an entity
 */
abstract class EntityModel extends DoctrineModel
{
    /** @var object */
    protected $entity;


    /**
     * Constructs a new entity model
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param $entity
     */
    public function __construct (ContainerInterface $container, $entity)
    {
        parent::__construct($container);
        $this->entity = $entity;
    }



    /**
     * Returns the entity
     *
     * @return object
     */
    public function getEntity ()
    {
        return $this->entity;
    }



    /**
     * Stores the entity in the database
     */
    public function store ()
    {
        $em = $this->getEntityManager();
        $em->persist($this->entity);
        $em->flush($this->entity);
    }
}