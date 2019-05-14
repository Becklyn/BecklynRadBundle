<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

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
     * @param array $classes
     *
     * @return string
     */
    public function formatClassNames (array $classes)
    {
        $result = [];

        foreach ($classes as $class => $enabled)
        {
            // support key less values
            if (\is_int($class))
            {
                $result[] = $enabled;
            }
            elseif ($enabled)
            {
                $result[] = $class;
            }
        }

        return \implode(" ", $result);
    }


    /**
     * @inheritDoc
     */
    public function getFunctions () : array
    {
        return [
            new TwigFunction("classnames", [$this, "formatClassNames"]),
        ];
    }


    /**
     * @inheritDoc
     */
    public function getFilters () : array
    {
        return [
            new TwigFilter("appendToKey", [$this, "appendToKey"]),
        ];
    }
}
