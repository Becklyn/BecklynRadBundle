<?php

namespace Becklyn\RadBundle\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 *
 */
class AuthenticationHelper
{
    private TokenStorageInterface $tokenStorage;
    private RequestStack $requestStack;
    private SessionInterface $session;

    public function __construct (TokenStorageInterface $tokenStorage, RequestStack $requestStack)
    {
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->session = $this->requestStack->getSession();
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

        $this->tokenStorage->setToken($token);
        $this->session->set("_security_{$firewall}", serialize($token));
    }



    /**
     * Deauthenticates the current user.
     *
     * Warning: it removes the complete session.
     */
    protected function deauthenticateUser ()
    {
        $this->tokenStorage->setToken(null);
        $this->session->invalidate();
    }
}
