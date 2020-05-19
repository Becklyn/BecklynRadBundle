<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Search;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;

final class SimpleEntitySearchHelper
{
    /** @var SimpleQueryTokenizer */
    private $tokenizer;

    /**
     */
    public function __construct (SimpleQueryTokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }


    /**
     * Applies the search to the given query builder
     */
    public function applySearch (QueryBuilder $queryBuilder, ?string $query, array $fields) : QueryBuilder
    {
        $query = \trim((string) $query);

        if ("" === $query)
        {
            return $queryBuilder;
        }

        $tokenized = $this->tokenizer->transformToQuery($query);
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
}
