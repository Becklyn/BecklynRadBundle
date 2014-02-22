<?php

namespace Becklyn\RadBundle\Service;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class SimpleRouteVoter extends ContainerAware implements VoterInterface
{
    /**
     * Checks whether an item is current.
     *
     * If the voter is not able to determine a result,
     * it should return null to let other voters do the job.
     *
     * @param ItemInterface $item
     *
     * @return boolean|null
     */
    public function matchItem (ItemInterface $item)
    {
        $request = $this->container->get("request");

        if (is_null($request))
        {
            return null;
        }

        $route = $request->attributes->get('_route');
        if (is_null($route))
        {
            return null;
        }

        $itemRoutes = (array) $item->getExtra('routes', array());
        if (in_array($route, $itemRoutes, true))
        {
            return true;
        }

        foreach ($itemRoutes as $itemRoute)
        {
            if (is_array($itemRoute) && isset($itemRoute["route"]) && ($route === $itemRoute["route"]))
            {
                return true;
            }
        }

        return null;
    }
}