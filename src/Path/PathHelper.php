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
     */
    public static function join (...$paths) : string
    {
        $normalized = [];

        foreach ($paths as $index => $path)
        {
            if (0 !== $index)
            {
                /**
                 * @phpstan-ignore-next-line
                 */
                $path = \ltrim($path, "/");
            }

            if ($index !== \count($paths) - 1)
            {
                $path = \rtrim($path, "/");
            }

            $normalized[] = $path;
        }

        /**
         * @phpstan-ignore-next-line
         */
        return \implode("/", $normalized);
    }
}
