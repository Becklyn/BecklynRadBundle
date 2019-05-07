<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Bundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Base class to use in your bundle to easily create an extension.
 */
class BundleExtension extends Extension
{
    /**
     * @var string
     */
    private $bundlePath;


    /**
     * @var string
     */
    private $alias;


    /**
     * @param string $bundlePath
     * @param string $alias
     */
    public function __construct (string $bundlePath, string $alias)
    {
        $this->bundlePath = $bundlePath;
        $this->alias = $alias;
    }

    /**
     * @inheritDoc
     */
    public function load (array $configs, ContainerBuilder $container)
    {
        $configDir = "{$this->bundlePath}/Resources/config";

        if (\is_file("{$configDir}/services.yaml"))
        {
            $loader = new YamlFileLoader($container, new FileLocator($configDir));
            $loader->load("services.yaml");
        }
    }


    /**
     * @inheritDoc
     */
    public function getAlias ()
    {
        return $this->alias;
    }
}
