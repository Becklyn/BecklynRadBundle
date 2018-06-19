<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Exception;


/**
 * Generic exception that marks, that the entity removal may be blocked by database (foreign keys, etc..) or
 * semantic constraints (e.g. "can't remove main category").
 */
class EntityRemovalBlockedException extends \DomainException
{
    /**
     * @var object
     */
    private $entity;


    /**
     * @inheritDoc
     *
     * @param object Entity
     */
    public function __construct ($entity, string $message, \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->entity = $entity;
    }


    /**
     * @return object
     */
    public function getEntity ()
    {
        return $this->entity;
    }
}
