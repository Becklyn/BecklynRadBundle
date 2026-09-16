<?php

namespace Becklyn\RadBundle\AppBundle;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


/**
 * The default configuration class
 */
class BundleConfiguration implements ConfigurationInterface
{
    /**
     * @param Bundle $bundle
     * @param string $alias
     */
    public function __construct(
        private readonly Bundle $bundle,
        /**
         * The bundle name alias
         */
        private $alias
    )
    {
    }



    /**
     * Generates the configuration tree builder.
     */
    public function getConfigTreeBuilder () : TreeBuilder
    {
        $treeBuilder = new TreeBuilder("becklyn_rad");
        $rootNode    = $treeBuilder->getRootNode();

        // process user configuration
        $this->bundle->buildConfiguration($rootNode);

        return $treeBuilder;
    }
}
