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
     * @var Bundle
     */
    private $bundle;


    /**
     * The bundle name alias
     *
     * @var string
     */
    private $alias;



    /**
     * @param Bundle $bundle
     * @param string $alias
     */
    public function __construct (Bundle $bundle, $alias)
    {
        $this->bundle = $bundle;
        $this->alias  = $alias;
    }



    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder ()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root($this->alias);

        // process user configuration
        $this->bundle->buildConfiguration($rootNode);

        return $treeBuilder;
    }
}
