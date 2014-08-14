<?php

namespace Becklyn\RadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
        $this->addFlashMessage($message, $type, "admin-general", $title);
    }



    /**
     * Adds a flash message
     *
     * @param string $message
     * @param string $type
     * @param string $flashBagName
     * @param null|string $title
     *
     * @throws \InvalidArgumentException
     */
    protected function addFlashMessage ($message, $type = "success", $flashBagName = "messages", $title = null)
    {
        $allowedTypes = ["warning", "error", "success", "info", "danger"];

        if (!in_array($type, $allowedTypes, true))
        {
            throw new \InvalidArgumentException("Unknown flash type: {$type}");
        }

        $this->get('session')->getFlashBag()->add($flashBagName, [
                "message" => $message,
                "title"   => $title,
                "type"    => $type
            ]);
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
        $allErrors   = array();
        $formName    = $form->getName();
        $fieldPrefix = !empty($formName) ? "{$formName}_" : "";

        foreach ($form->all() as $children)
        {
            $errors = $children->getErrors();
            if (!empty($errors))
            {
                $allErrors["{$fieldPrefix}{$children->getName()}"] = array_map(
                    function (FormError $error)
                    {
                        return $error->getMessage();
                    },
                    is_array($errors) ? $errors : iterator_to_array($errors)
                );
            }
        }

        if ($form->getErrors()->count() > 0)
        {
            foreach ($form->getErrors() as $topLevelError)
            {
                $allErrors["__global"][] = $topLevelError->getMessage();
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



    /**
     * Authenticates as the given user.
     *
     * @param UserInterface $user       the user to authenticate
     * @param string        $firewall   the firewall to authenticate against
     */
    protected function authenticateUser (UserInterface $user, $firewall = "secured_area")
    {
        $token = new UsernamePasswordToken($user, null, "profile_area", $user->getRoles());
        $this->get("security.context")->setToken($token);
        $this->get("session")->set('_security_secured_area', serialize($token));
    }



    /**
     * Deauthenticates the current user.
     *
     * Warning: it removes the complete session.
     */
    protected function deauthenticateUser ()
    {
        $this->get("security.context")->setToken(null);
        $this->get("session")->invalidate();
    }
}
