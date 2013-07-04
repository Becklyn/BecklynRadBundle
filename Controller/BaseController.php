<?php

namespace OAGM\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}