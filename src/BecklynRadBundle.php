<?php declare(strict_types=1);

namespace Becklyn\Rad;

use Becklyn\Rad\DependencyInjection\DoctrineExtensionsCompilerPass;
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
    }
}
