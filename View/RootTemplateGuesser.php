<?php

namespace Becklyn\RadBundle\View;

use Doctrine\Common\Util\ClassUtils;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;


/**
 *
 */
class RootTemplateGuesser
{
    /**
     * @param string  $controller
     * @param Request $request
     *
     * @return string
     */
    public function guessTemplateName ($controller, Request $request)
    {
        $className = class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($controller[0]) : get_class($controller[0]);

        if (!preg_match('/Controller\\\(?P<controller_name>.+)Controller$/', $className, $matchController)) {
            throw new \InvalidArgumentException(sprintf('The "%s" class does not look like a controller class (it must be in a "Controller" sub-namespace and the class name must end with "Controller")', get_class($controller[0])));
        }
        if (!preg_match('/^(?P<action_name>.+)Action$/', $controller[1], $matchAction)) {
            throw new \InvalidArgumentException(sprintf('The "%s" method does not look like an action method (it does not end with Action)', $controller[1]));
        }

        $filename = Container::underscore($matchController["controller_name"]) . "/" . Container::underscore($matchAction["action_name"]);
        return new TemplateReference(null, null, $filename, $request->getRequestFormat(), "twig");
    }
}
