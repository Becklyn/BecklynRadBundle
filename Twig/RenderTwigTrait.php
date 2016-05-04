<?php

namespace Becklyn\RadBundle\Twig;


/**
 * Trait for adding render() support in twig extensions
 */
trait RenderTwigTrait
{
    /**
     * @var \Twig_Environment
     */
    private $twig;



    /**
     * @param \Twig_Environment $twig
     */
    public function setTwig (\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }



    /**
     * Renders the given template
     *
     * @param string $name
     * @param array  $context
     *
     * @return string
     */
    public function render ($name, array $context = [])
    {
        if (null === $this->twig)
        {
            throw new \RuntimeException("No twig instance set for rendering templates.");
        }

        return $this->twig->render($name, $context);
    }
}
