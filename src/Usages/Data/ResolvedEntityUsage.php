<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Usages\Data;

/**
 * A already resolved usage.
 *
 * Without the group (as it is handled externally) and the link is already resolved.
 *
 * @deprecated Use becklyn/entity-admin instead.
 */
final class ResolvedEntityUsage
{
    /**
     * @var array
     */
    private $labels;


    /**
     * @var string|null
     */
    private $type;


    /**
     * @var string|null
     */
    private $url;


    /**
     */
    public function __construct (array $labels, ?string $type, ?string $url)
    {
        $this->labels = $labels;
        $this->type = $type;
        $this->url = $url;
    }


    /**
     */
    public function getLabels () : array
    {
        return $this->labels;
    }


    /**
     */
    public function getType () : ?string
    {
        return $this->type;
    }


    /**
     */
    public function getUrl () : ?string
    {
        return $this->url;
    }
}
