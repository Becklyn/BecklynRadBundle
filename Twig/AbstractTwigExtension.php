<?php

namespace Becklyn\RadBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class AbstractTwigExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName ()
    {
        return get_class($this);
    }
}
