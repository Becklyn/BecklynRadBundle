<?php

namespace Becklyn\RadBundle\AppBundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
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



    /**
     * @inheritdoc
     *
     * @return string
     * @throws BadMethodCallException
     */
    public function getAlias ()
    {
        $className = get_class($this->bundle);
        if (substr($className, -6) != 'Bundle') {
            throw new BadMethodCallException('This extension does not follow the naming convention; you must overwrite the getAlias() method.');
        }

        $classBaseName = substr(strrchr($className, '\\'), 1, -6);

        return Container::underscore($classBaseName);
    }
}
