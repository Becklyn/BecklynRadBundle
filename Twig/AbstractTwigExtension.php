<?php

namespace Becklyn\RadBundle\Twig;


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
