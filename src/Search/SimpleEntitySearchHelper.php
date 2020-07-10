<?php declare(strict_types=1);

namespace Becklyn\Rad\Search;

use Becklyn\Rad\Exception\Search\InvalidSearchArgumentsException;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;

final class SimpleEntitySearchHelper
{
    public const MODE_PREFIX = SimpleQueryTokenizer::MODE_PREFIX;
    public const MODE_EVERYWHERE = SimpleQueryTokenizer::MODE_EVERYWHERE;
    private SimpleQueryTokenizer $tokenizer;

    /**
     */
    public function __construct (SimpleQueryTokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }


    /**
     * Applies the search to the given query builder.
     * Searches single text fields.
     */
    public function applySearch (
        QueryBuilder $queryBuilder,
        ?string $query,
        array $fields,
        bool $mode = self::MODE_PREFIX
    ) : QueryBuilder
    {
        $query = \trim((string) $query);

        if ("" === $query)
        {
            return $queryBuilder;
        }

        $tokenized = $this->tokenizer->transformToQuery($query, $mode);
        $expr = new Orx();

        foreach ($fields as $field)
        {
            $expr->add(
                new Comparison($field, "LIKE", ":__searchTerm")
            );
        }

        return $queryBuilder
            ->andWhere($expr)
            ->setParameter("__searchTerm", $tokenized);
    }


    /**
     * Applies the search to the given query builder.
     * Searches a JSON structure for strings matching the query.
     *
     * The $fields parameter accepts the following values:
     *
     * - An indexed array will search through an entire JSON: `0 => "queryTableAlias.dbJsonField"`
     *
     * - An associative array with a single JSON path: `"queryTableAlias.dbJsonField" => "$.email"`
     *
     * - An associative array with multiple JSON paths: `"queryTableAlias.dbJsonField" => ["$.email", "$.firstName", "$.lastName", "$**some-nested-key", …]`
     *
     * Please see {@see https://dev.mysql.com/doc/refman/8.0/en/json-search-functions.html#function_json-search}
     * and {@see https://dev.mysql.com/doc/refman/8.0/en/json.html#json-path-syntax} to learn more about the JSON path syntax.
     *
     * @param array $fields An indexed based array to search the entire JSON, or an associative array where the key is the database field
     *                      and the value a JSON path (e.g. „$.email”), or an array of JSON paths, to search in specific paths.
     */
    public function applyJsonSearch (
        QueryBuilder $queryBuilder,
        ?string $query,
        array $fields,
        bool $mode = self::MODE_PREFIX
    ) : QueryBuilder
    {
        $query = \trim((string) $query);

        if ("" === $query)
        {
            return $queryBuilder;
        }

        $tokenized = $this->tokenizer->transformToQuery($query, $mode);
        $expr = new Orx();

        foreach ($fields as $field => $jsonPaths)
        {
            if (\is_int($field))
            {
                $expr->add(
                    new Comparison(
                        new Func("JSON_SEARCH", [
                            $jsonPaths,
                            "'one'",
                            ":__searchTerm",
                        ]),
                        "IS NOT",
                        "NULL"
                    )
                );
            }
            else
            {
                $jsonPaths = !\is_array($jsonPaths)
                    ? [$jsonPaths]
                    : $jsonPaths;

                foreach ($jsonPaths as $jsonPath)
                {
                    if (false !== \strpos($jsonPath, "'"))
                    {
                        throw new InvalidSearchArgumentsException(\sprintf(
                            "Received pre-escaped JSON path argument „%s” for field alias „%s”. The calling code must not pre-escape the JSON path, as that is done during query building.",
                            $jsonPath,
                            $field
                        ));
                    }

                    $expr->add(
                        new Comparison(
                            new Func("JSON_SEARCH", [
                                $field,
                                "'one'",
                                ":__searchTerm",
                                "NULL",
                                "'{$jsonPath}'",
                            ]),
                            "IS NOT",
                            "NULL"
                        )
                    );
                }
            }
        }

        return $queryBuilder
            ->andWhere($expr)
            ->setParameter("__searchTerm", $tokenized);
    }
}
