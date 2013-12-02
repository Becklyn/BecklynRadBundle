<?php

namespace Becklyn\RadBundle\Service;

use Becklyn\RadBundle\Helper\Pagination;

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
            "@BecklynRad/Pagination/pagination.html.twig",
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