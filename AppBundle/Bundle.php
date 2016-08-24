<?php

namespace Becklyn\RadBundle\AppBundle;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;


/**
 * Base bundle. You should just inherit from this bundle and optionally override {@see self::buildConfiguration()}
 * and {@link self::buildContainer()} to configure your configuration.
 */
class Bundle extends BaseBundle
{
    /**
     * Builds the configuration for this bundle
     *
     * @param ArrayNodeDefinition|NodeDefinition|NodeParentInterface $root
     */
    public function buildConfiguration (NodeParentInterface $root)
    {
    }



    /**
     * Apply the processed configuration to the container
     *
     * @param array            $config     the processed configuration
     * @param ContainerBuilder $container
     */
    public function buildContainer (array $config, ContainerBuilder $container)
    {
    }



    /**
     * @inheritdoc
     */
    public function getContainerExtension ()
    {
        return new BundleExtension($this);
    }
}
