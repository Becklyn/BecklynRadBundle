<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Usages\Data;

/**
 * A already resolved usage.
 *
 * Without the group (as it is handled externally) and the link is already resolved.
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
    private $url;


    /**
     */
    public function __construct (array $labels, ?string $url)
    {
        $this->labels = $labels;
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
    public function getUrl () : ?string
    {
        return $this->url;
    }


    /**
     */
    public function toArray () : array
    {
        return [
            "name" => $this->labels,
            "url" => $this->url,
        ];
    }
}
