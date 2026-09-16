<?php

namespace Becklyn\RadBundle\Twig;


use Twig\Extension\AbstractExtension;

/**
 *
 */
class AbstractTwigExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getName ()
    {
        return static::class;
    }
}
