<?php declare(strict_types=1);

namespace Becklyn\Rad\Search;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;

final class SimpleEntitySearchHelper
{
    public const MODE_PREFIX = SimpleQueryTokenizer::MODE_PREFIX;
    public const MODE_EVERYWHERE = SimpleQueryTokenizer::MODE_EVERYWHERE;

    /** @var SimpleQueryTokenizer */
    private $tokenizer;

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

        foreach ($fields as $field)
        {
            $expr->add(
                new Comparison(
                    new Func("JSON_SEARCH", [
                        $field,
                        "'one'",
                        ":__searchTerm",
                    ]),
                    "IS NOT",
                    "NULL"
                )
            );
        }

        return $queryBuilder
            ->andWhere($expr)
            ->setParameter("__searchTerm", $tokenized);
    }
}
