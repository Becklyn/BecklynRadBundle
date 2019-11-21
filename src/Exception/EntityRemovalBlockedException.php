<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Exception;

/**
 * Generic exception that marks, that the entity removal may be blocked by database (foreign keys, etc..) or
 * semantic constraints (e.g. "can't remove main category").
 */
class EntityRemovalBlockedException extends RadException
{
    /**
     * @var object[]
     */
    private $entities;


    /**
     * @inheritDoc
     *
     * @param object|object[] $entities
     */
    public function __construct ($entities, string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, $previous);
        $this->entities = \is_array($entities) ? $entities : [$entities];
    }


    /**
     * @return object[]
     */
    public function getEntities () : array
    {
        return $this->entities;
    }
}
