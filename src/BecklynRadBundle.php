<?php declare(strict_types=1);

namespace Becklyn\RadBundle;

use Becklyn\RadBundle\Bundle\BundleExtension;
use Becklyn\RadBundle\DependencyInjection\DoctrineExtensionsCompilerPass;
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
    }
}
