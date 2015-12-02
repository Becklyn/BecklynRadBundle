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

        $this->addChildErrors($form, $fieldPrefix, $allErrors);

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
     * Adds all child errors to the mapping of errors
     *
     * @param FormInterface $form
     * @param string        $fieldPrefix
     * @param array         $allErrors
     */
    private function addChildErrors (FormInterface $form, $fieldPrefix, array &$allErrors)
    {
        foreach ($form->all() as $children)
        {
            $childErrors = $children->getErrors();
            $fieldName = ltrim("{$fieldPrefix}{$children->getName()}");

            if (0 < count($childErrors))
            {
                $allErrors[$fieldName] = array_map(
                    function (FormError $error)
                    {
                        return $error->getMessage();
                    },
                    is_array($childErrors) ? $childErrors : iterator_to_array($childErrors)
                );
            }

            $this->addChildErrors($children, "{$fieldName}_", $allErrors);
        }
    }



    /**
     * Authenticates as the given user.
     *
     * @param UserInterface $user       the user to authenticate
     * @param string        $firewall   the firewall to authenticate against
     */
    protected function authenticateUser (UserInterface $user, $firewall = "secured_area")
    {
        $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
        $this->get("security.context")->setToken($token);
        $this->get("session")->set("_security_{$firewall}", serialize($token));
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
