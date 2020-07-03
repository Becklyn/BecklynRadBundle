<?php declare(strict_types=1);

namespace Becklyn\RadBundle;

use Becklyn\RadBundle\DependencyInjection\DoctrineExtensionsCompilerPass;
use Becklyn\RadBundle\Usages\EntityUsagesProviderInterface;
use Becklyn\RadBundles\Bundle\BundleExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 *
 */
class BecklynRadBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function getContainerExtension ()
    {
        return new BundleExtension($this);
    }


    /**
     */
    public function build (ContainerBuilder $container) : void
    {
        $container->addCompilerPass(new DoctrineExtensionsCompilerPass());

        $container
            ->registerForAutoconfiguration(EntityUsagesProviderInterface::class)
            ->addTag("entity_usages.provider");
    }
}
