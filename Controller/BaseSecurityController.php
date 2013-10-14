<?php

namespace OAGM\BaseBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;

abstract class BaseSecurityController extends BaseController
{
    /**
     * Returns the template name of the login controller
     *
     * @return mixed
     */
    protected function getLoginActionTemplate()
    {
        $namespaceParts = explode("\\", trim(get_class($this), "\\"));
        $bundle = $namespaceParts[0] . str_replace("Bundle", "", $namespaceParts[1]);
        return "@{$bundle}/Security/login.html.twig";
    }



    /**
     * Handles the login action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction ()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            $this->getLoginActionTemplate(),
            array(
                'username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'    => $error,
            )
        );
    }
}