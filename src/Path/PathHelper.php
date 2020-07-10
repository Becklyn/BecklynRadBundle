<?php declare(strict_types=1);

namespace Becklyn\Rad\Path;

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
    public static function join (string ...$paths) : string
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
