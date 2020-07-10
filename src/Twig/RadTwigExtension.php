<?php declare(strict_types=1);

namespace Becklyn\Rad\Twig;

use Becklyn\Rad\Html\DataContainer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class RadTwigExtension extends AbstractExtension
{
    private DataContainer $dataContainer;


    /**
     */
    public function __construct (DataContainer $dataContainer)
    {
        $this->dataContainer = $dataContainer;
    }

    /**
     *
     */
    public function appendToKey (array $map, string $key, string $append) : array
    {
        $value = $map[$key] ?? "";
        $map[$key] = \trim("{$value} {$append}");
        return $map;
    }


    /**
     *
     */
    public function formatClassNames (array $classes) : string
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
        $safeHtml = ["is_safe" => ["html"]];

        return [
            new TwigFunction("classnames", [$this, "formatClassNames"]),
            new TwigFunction("data_container", [$this->dataContainer, "renderToHtml"], $safeHtml),
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
