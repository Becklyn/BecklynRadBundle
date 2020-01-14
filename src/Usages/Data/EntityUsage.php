<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Usages\Data;

use Becklyn\RadBundle\Route\DeferredRoute;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * An unresolved entity usage. Optimized for ease of use.
 *
 * Neither the group nor the route is resolved yet.
 */
final class EntityUsage
{
    /**
     * @var string[]
     */
    private $labels;


    /**
     * @var string|null
     */
    private $group;


    /**
     * A label that describes the type, like a text or an icon key. Depends on the UI on what to do with this.
     *
     * @var string|null
     */
    private $type;


    /**
     * @var DeferredRoute|null
     */
    private $link;


    /**
     * @param string|string[] $labels
     * @param string|null     $group  The group name. Will be translated in the `backend` domain.
     */
    public function __construct ($labels, ?string $group = null, ?string $type = null, ?DeferredRoute $link = null)
    {
        $this->labels = \is_array($labels) ? $labels : [$labels];
        $this->group = $group;
        $this->type = $type;
        $this->link = $link;
    }


    /**
     */
    public function getLabels () : array
    {
        return $this->labels;
    }


    /**
     */
    public function getGroup () : ?string
    {
        return $this->group;
    }


    /**
     */
    public function getType () : ?string
    {
        return $this->type;
    }


    /**
     */
    public function getLink () : ?DeferredRoute
    {
        return $this->link;
    }


    /**
     * Resolves the entity usage
     */
    public function resolve (UrlGeneratorInterface $urlGenerator) : ResolvedEntityUsage
    {
        return new ResolvedEntityUsage(
            $this->labels,
            $this->type,
            null !== $this->link
                ? $this->link->generate($urlGenerator)
                : null
        );
    }
}
