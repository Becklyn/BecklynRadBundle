<?php

namespace Becklyn\RadBundle;

use Becklyn\RadBundle\DependencyInjection\MonitoringCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class BecklynRadBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build (ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MonitoringCompilerPass());
    }
}
