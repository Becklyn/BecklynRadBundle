<?php

namespace OAGM\BaseBundle\Helper;

use Doctrine\ORM\QueryBuilder;

class SearchHelper
{
    /**
     * @var string[]
     */
    private $searchFields;


    /**
     * @param string[] $searchFields
     */
    public function __construct (array $searchFields)
    {
        $this->searchFields = $searchFields;
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
            foreach ($this->searchFields as $searchField)
            {
                foreach ($tokens as $tokenId => $token)
                {
                    $where[] = $queryBuilder->expr()->like("{$table}.{$searchField}", ":token{$tokenId}");
                }
            }
        }

        foreach ($tokens as $tokenId => $token)
        {
            $queryBuilder->setParameter(":token{$tokenId}", "%{$token}%");
        }

        $queryBuilder->andWhere( call_user_func_array([$queryBuilder->expr(), "orX"], $where) );
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