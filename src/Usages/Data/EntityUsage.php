<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Usages\Data;

use Becklyn\RadBundle\Route\DeferredRoute;

/**
 * An unresolved entity usage. Optimized for ease of use.
 *
 * Neither the group nor the route is resolved yet.
 */
class EntityUsage
{
    /**
     * @var string
     */
    private $name;


    /**
     * @var string|null
     */
    private $group;


    /**
     * @var DeferredRoute|null
     */
    private $link;


    /**
     * @param string|null $group The group name. Will be translated in the `backend` domain.
     */
    public function __construct (string $name, ?string $group = null, ?DeferredRoute $link = null)
    {
        $this->name = $name;
        $this->group = $group;
        $this->link = $link;
    }


    /**
     */
    public function getName () : string
    {
        return $this->name;
    }


    /**
     */
    public function getGroup () : ?string
    {
        return $this->group;
    }


    /**
     */
    public function getLink () : ?DeferredRoute
    {
        return $this->link;
    }
}
