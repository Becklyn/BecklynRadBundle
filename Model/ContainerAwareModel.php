<?php

namespace OAGM\BaseBundle\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class ContainerAwareModel
{
    /**
     * The service container
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;



    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct (ContainerInterface $container)
    {
        $this->container = $container;
    }



    /**
     * Shortcut for access to service container
     *
     * @param string $service
     * @return object
     */
    protected function get ($service)
    {
        return $this->container->get($service);
    }
}