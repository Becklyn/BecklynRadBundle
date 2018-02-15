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

        // load services file
        $this->loadServicesDefinitions($container);

        // build container content
        $this->bundle->buildContainer($processedConfig, $container);
    }


    /**
     * Loads the services definition files
     *
     * @param string $environment
     */
    private function loadServicesDefinitions (ContainerBuilder $container) : void
    {
        $environment = $container->getParameter("kernel.environment");
        $possibleServiceFiles = [
            "services_{$environment}.yaml",
            "services.yaml",
            "services_{$environment}.yml",
            "services.yml",
        ];
        $configPath = "{$this->bundle->getPath()}/Resources/config";
        $fileLocator = new FileLocator($configPath);
        $loader = new YamlFileLoader($container, $fileLocator);

        foreach ($possibleServiceFiles as $servicesFile)
        {
            if (\is_file("{$configPath}/{$servicesFile}"))
            {
                $loader->load($servicesFile);
            }
        }
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
