<?php

namespace OAGM\BaseBundle\Service;

use Bogner\AdminBundle\Service\AbstractTwigExtension;
use OAGM\BaseBundle\Helper\Pagination;

class PaginationTwigExtension extends AbstractTwigExtension
{
    /**
     * Renders the pagination
     *
     * @param Pagination $pagination
     *
     * @param string $route
     * @param array $additionalRouteParameters
     * @param string $pageParameterName
     *
     * @return string
     */
    public function pagination (Pagination $pagination, $route, array $additionalRouteParameters = array(), $pageParameterName = "page")
    {
        return $this->render(
            "OAGMBaseBundle:Pagination:pagination.html.twig",
            array(
                "pagination" => $pagination,
                "route" => $route,
                "routeParameters" => $additionalRouteParameters,
                "pageParameterName" => $pageParameterName
            )
        );
    }



    /**
     * @return \Twig_Function[]
     */
    public function getFunctions ()
    {
        return array(
            "pagination" => new \Twig_Function_Method($this, "pagination", array("is_safe" => array("html")))
        );
    }
}