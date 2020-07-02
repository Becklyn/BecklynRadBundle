<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Bundle;

use Becklyn\RadBundles\Bundle\BundleExtension as NewBundleExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

if (\class_exists(NewBundleExtension::class))
{
    class BundleExtension extends NewBundleExtension
    {
    }
}
else
{
    /**
     * Base class to use in your bundle to easily create an extension.
     *
     * @deprecated Please use the version from `becklyn/rad-bundles` instead.
     */
    class BundleExtension extends Extension
    {
        /** @var BundleInterface */
        private $bundle;


        /** @var string|null */
        private $alias;


        /**
         */
        public function __construct (
            BundleInterface $bundle,
            ?string $alias = null
        )
        {
            $this->bundle = $bundle;
            $this->alias = $alias;
        }

        /**
         * @inheritDoc
         */
        public function load (array $configs, ContainerBuilder $container) : void
        {
            // first try Symfony 5+ structure
            $configDir = "{$this->bundle->getPath()}/config";

            // then fall back to legacy structure
            if (!\is_dir($configDir))
            {
                $configDir = "{$this->bundle->getPath()}/Resources/config";
            }

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
            if (null !== $this->alias)
            {
                return $this->alias;
            }

            // use default naming convention
            $basename = \preg_replace('/Bundle$/', '', $this->bundle->getName());
            return Container::underscore($basename);
        }
    }
}
