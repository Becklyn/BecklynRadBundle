<?php

namespace Becklyn\RadBundle\Controller;

use Becklyn\RadBundle\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;


/**
 * Base security implementations
 */
class SecurityController extends Controller
{
    /**
     * Handles the generic login
     *
     * @param string $template
     *
     * @return Response
     */
    public function loginAction ($template)
    {
        $authenticationUtils = $this->get("security.authentication_utils");

        $loginForm = $this->createForm(LoginForm::class, [
            "username" => $authenticationUtils->getLastUsername(),
        ], [
            "action" => $this->generateUrl("_login_check"),
            "method" => "post",
        ]);

        if (null !== ($loginError = $authenticationUtils->getLastAuthenticationError()))
        {
            $loginForm->addError(new FormError($loginError->getMessage()));
        }

        return $this->render($template, [
            "loginForm" => $loginForm->createView(),
        ]);
    }
}
