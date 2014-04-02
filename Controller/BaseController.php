<?php

namespace Becklyn\RadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class BaseController extends Controller
{
    /**
     * Returns a redirection to the given route
     *
     * @param string $route
     * @param array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectPath ($route, array $parameters = array())
    {
        return $this->redirect(
            $this->generateUrl($route, $parameters)
        );
    }



    /**
     * Adds a flash message
     *
     * @deprecated use {@link self::addFlashMessage()} instead
     *
     * @param string $title
     * @param string $message
     * @param string $type
     *
     * @throws \InvalidArgumentException
     */
    protected function addFlash ($message, $title = null, $type = "warning")
    {
        $this->addFlashMessage($message, $type, $title);
    }



    /**
     * Adds a flash message
     *
     * @param string $message
     * @param string $type
     * @param null|string $title
     *
     * @throws \InvalidArgumentException
     */
    protected function addFlashMessage ($message, $type = "success", $title = null)
    {
        $allowedTypes = ["warning", "error", "success", "info"];

        if (!in_array($type, $allowedTypes, true))
        {
            throw new \InvalidArgumentException("Unknown flash type: {$type}");
        }

        $data = [
            "message" => $message,
            "title"   => $title,
            "type"    => $type
        ];

        /** @var $flashBag FlashBagInterface */
        $flashBag = $this->get('session')->getFlashBag();
        $flashBag->add("admin-general", $data);
    }



    /**
     * Returns the form error mapping for easier marking of form errors in
     * javascript handler code.
     *
     * @param FormInterface $form
     *
     * @return array
     */
    protected function getFormErrorMapping (FormInterface $form)
    {
        $allErrors = array();

        foreach ($form->all() as $children)
        {
            $errors = $children->getErrors();
            if (!empty($errors))
            {
                $allErrors["{$form->getName()}_{$children->getName()}"] = array_map(
                    function (FormError $error)
                    {
                        return $error->getMessage();
                    },
                    $errors
                );
            }
        }

        return $allErrors;
    }



    /**
     * Wrapper for isGranted by the security context
     *
     * @param mixed      $attributes
     * @param mixed|null $object
     *
     * @return bool
     */
    protected function isGranted ($attributes, $object = null)
    {
        return $this->get("security.context")->isGranted($attributes, $object = null);
    }
}