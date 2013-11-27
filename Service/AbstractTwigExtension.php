<?php

namespace Becklyn\RadBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class AbstractTwigExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;



    /**
     * @param ContainerInterface $container
     */
    public function __construct (ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName ()
    {
        return get_class($this);
    }



    /**
     * Renders a given template
     *
     * @param string $template
     * @param array $variables
     *
     * @return string
     */
    protected function render ($template, array $variables = array())
    {
        /** @var $twig \Symfony\Bridge\Twig\TwigEngine */
        $twig = $this->container->get("templating");

        return $twig->render($template, $variables);
    }
}