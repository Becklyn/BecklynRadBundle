<?php

namespace Becklyn\RadBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;

/**
 * The RootTemplate class handles the RootTemplate annotation parts.
 *
 * @Annotation
 */
class RootTemplate extends ConfigurationAnnotation
{
    /**
     * The template reference.
     *
     * @var TemplateReference
     */
    protected $template;


    /**
     * Sets the template logic name.
     *
     * @param string $template The template logic name
     */
    public function setValue($template)
    {
        $this->setTemplate($template);
    }

    /**
     * Returns the template reference.
     *
     * @return TemplateReference
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Sets the template reference.
     *
     * @param TemplateReference|string $template The template reference
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Returns the annotation alias name.
     *
     * @return string
     * @see ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'root_template';
    }

    /**
     * Only one template directive is allowed
     *
     * @return bool
     * @see ConfigurationInterface
     */
    public function allowArray()
    {
        return false;
    }
}
