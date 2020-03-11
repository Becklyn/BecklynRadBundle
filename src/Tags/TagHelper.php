<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Tags;

use Becklyn\RadBundle\Exception\TagNormalizationException;

class TagHelper
{
    /**
     * @param array<string|TagInterface|mixed> $tags
     *
     * @return string[]
     */
    public static function getTagLabels (array $tags) : array
    {
        $labels = [];

        foreach ($tags as $tag)
        {
            if (null === $tag)
            {
                continue;
            }

            if ($tag instanceof TagInterface)
            {
                $labels[] = $tag->getTagLabel();
                continue;
            }

            if (\is_string($tag))
            {
                $labels[] = $tag;
                continue;
            }

            throw new TagNormalizationException(\sprintf(
                "Can't transform value of type '%s'.",
                \is_object($tag) ? \get_class($tag) : \gettype($tag)
            ));
        }

        return $labels;
    }
}
