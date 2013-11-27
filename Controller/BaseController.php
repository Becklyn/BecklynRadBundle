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
     * @param string $title
     * @param string $message
     * @param string $type
     *
     * @throws \InvalidArgumentException
     */
    protected function addFlash ($message, $title = null, $type = "warning")
    {
        $allowedTypes = array("warning", "error", "success", "info");
        if (!in_array($type, $allowedTypes, true))
        {
            throw new \InvalidArgumentException("Unknown flash type: {$type}");
        }

        $data = array(
            "message" => $message,
            "title"   => $title,
            "type"    => $type
        );

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
}