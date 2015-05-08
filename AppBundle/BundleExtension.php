<?php

namespace Becklyn\RadBundle\AppBundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


/**
 *
 */
class BundleExtension extends Extension
{
    /**
     * @var Bundle
     */
    private $bundle;



    /**
     * @param Bundle $bundle
     */
    public function __construct (Bundle $bundle)
    {
        $this->bundle = $bundle;
    }


    /**
     * {@inheritDoc}
     */
    public function load (array $config, ContainerBuilder $container)
    {
        $configuration = new BundleConfiguration($this->bundle, $this->getAlias());
        $processedConfig = $this->processConfiguration($configuration, $config);

        // check for the yaml file
        $fileLocator = new FileLocator("{$this->bundle->getPath()}/Resources/config");
        $loader = new YamlFileLoader($container, $fileLocator);
        $environment = $container->getParameter('kernel.environment');

        try {
            // check for environment suffixed services yml
            $loader->load("services_{$environment}.yml");
        }
        catch (\InvalidArgumentException $e)
        {
            // environment suffixed file not found, try regular file
            try {
                $loader->load("services.yml");
            }
            catch (\InvalidArgumentException $e)
            {
                // no services file found
                // just ignore
            }
        }

        // build container content
        $this->bundle->buildContainer($processedConfig, $container);
    }
}
