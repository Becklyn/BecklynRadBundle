<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Bundle;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

final class ConfigurableBundleExtension extends BundleExtension
{
    /** @var ConfigurationInterface */
    private $configuration;

    /** @var callable */
    private $configurator;


    /**
     */
    public function __construct (
        BundleInterface $bundle,
        ConfigurationInterface $configuration,
        callable $configurator,
        ?string $alias = null
    )
    {
        parent::__construct($bundle, $alias);
        $this->configuration = $configuration;
        $this->configurator = $configurator;
    }


    /**
     * @inheritDoc
     */
    public function load (array $configs, ContainerBuilder $container) : void
    {
        parent::load($configs, $container);

        $config = $this->processConfiguration($this->configuration, $configs);
        ($this->configurator)($config, $container);
    }
}
