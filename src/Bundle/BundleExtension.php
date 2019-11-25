<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Bundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Base class to use in your bundle to easily create an extension.
 */
class BundleExtension extends Extension
{
    /**
     * @var BundleInterface
     */
    private $bundle;


    /**
     */
    public function __construct (BundleInterface $bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * @inheritDoc
     */
    public function load (array $configs, ContainerBuilder $container) : void
    {
        $configDir = "{$this->bundle->getPath()}/Resources/config";

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
        // use default naming convention
        $basename = \preg_replace('/Bundle$/', '', $this->bundle->getName());
        return Container::underscore($basename);
    }
}
