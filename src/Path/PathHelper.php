<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Path;

/**
 * Several utility functions for manipulation paths.
 */
class PathHelper
{
    /**
     * Joins path segments to one full path.
     *
     * @param string[] ...$paths
     *
     * @return string
     */
    public static function join (...$paths) : string
    {
        $normalized = [];

        foreach ($paths as $index => $path)
        {
            if (0 !== $index)
            {
                $path = \ltrim($path, "/");
            }

            if ($index !== \count($paths) - 1)
            {
                $path = \rtrim($path, "/");
            }

            $normalized[] = $path;
        }

        return \implode("/", $normalized);
    }
}
