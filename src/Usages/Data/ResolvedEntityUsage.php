<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Usages\Data;

/**
 * A already resolved usage.
 *
 * Without the group (as it is handled externally) and the link is already resolved.
 */
class ResolvedEntityUsage
{
    /**
     * @var string
     */
    private $name;


    /**
     * @var string|null
     */
    private $url;


    /**
     */
    public function __construct (string $name, ?string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }


    /**
     */
    public function getName () : string
    {
        return $this->name;
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
            "name" => $this->name,
            "url" => $this->url,
        ];
    }
}
