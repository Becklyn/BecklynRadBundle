<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RadTwigExtension extends AbstractExtension
{
    /**
     * @param array  $map
     * @param string $key
     * @param string $append
     *
     * @return array
     */
    public function appendToKey (array $map, string $key, string $append) : array
    {
        $value = $map[$key] ?? "";
        $map[$key] = \trim("{$value} {$append}");
        return $map;
    }


    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters () : array
    {
        return [
            new TwigFilter("appendToKey", [$this, "appendToKey"]),
        ];
    }
}
