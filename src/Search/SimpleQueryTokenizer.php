<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Search;

/**
 * Simple query tokenizer, that first tokenizes a search query and then produces
 * a queryable placeholder string.
 */
final class SimpleQueryTokenizer
{
    public const MODE_PREFIX = true;
    public const MODE_EVERYWHERE = false;

    /**
     * Transforms the given raw query to a mysql-ready and queryable query string
     */
    public function transformToQuery (string $query, bool $mode = self::MODE_PREFIX)
    {
        $query = \trim($query);

        if ("" === $query)
        {
            return "";
        }

        // explode in single segments
        return \implode(
            " ",
            \array_map(
                function (string $segment) use ($mode)
                {
                    // we need to avoid wildcard injection, so escape both wildcard symbols from MySQL.
                    $segment = \addcslashes($segment, "%_");

                    // we use a prefix search for every token
                    return self::MODE_PREFIX === $mode
                        ? "{$segment}%"
                        : "%{$segment}%";
                },
                \preg_split('~\\s+~', $query)
            )
        );
    }
}
