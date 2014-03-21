<?php

namespace Becklyn\RadBundle\Helper;

use Doctrine\ORM\QueryBuilder;

class SearchHelper
{
    /**
     * @var string[]
     */
    private $searchFields;


    /**
     * Flag, whether the search is strict or not.
     *
     * strict     = must match ALL query tokens
     * not strict = must match ANY query token
     *
     * @var bool
     */
    private $strictSearch;



    /**
     * @param string[] $searchFields
     * @param bool $strictSearch
     */
    public function __construct (array $searchFields, $strictSearch = true)
    {
        $this->searchFields = $searchFields;
        $this->strictSearch = (bool) $strictSearch;
    }


    /**
     * Adds search parameters
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param string $searchString
     */
    public function addSearchParameters (QueryBuilder $queryBuilder, $searchString)
    {
        $tokens = $this->tokenizeSearchString($searchString);

        $tables = $queryBuilder->getRootAliases();
        $where = [];

        foreach ($tables as $table)
        {
            foreach ($tokens as $tokenId => $token)
            {
                $alternativeFields = [];

                foreach ($this->searchFields as $searchField)
                {
                    $alternativeFields[] = $queryBuilder->expr()->like("{$table}.{$searchField}", ":token{$tokenId}");
                }

                $where[] = call_user_func_array([$queryBuilder->expr(), 'orX'], $alternativeFields);
            }
        }

        // bind parameters
        foreach ($tokens as $tokenId => $token)
        {
            $queryBuilder->setParameter(":token{$tokenId}", "%{$token}%");
        }


        $globalOperator = $this->strictSearch ? 'andX' : 'orX';
        $queryBuilder->andWhere( call_user_func_array([$queryBuilder->expr(), $globalOperator], $where) );
    }



    /**
     * Tokenizes the search string
     *
     * @param string $searchString
     *
     * @return array
     */
    private function tokenizeSearchString ($searchString)
    {
        return explode(" ", $searchString);
    }
}