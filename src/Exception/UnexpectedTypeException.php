<?php declare(strict_types=1);

namespace Becklyn\Rad\Exception;

/**
 * Generic exception that represents a type error.
 */
final class UnexpectedTypeException extends \InvalidArgumentException implements RadException
{
    /**
     * @param mixed $value
     */
    public function __construct ($value, string $expectedType)
    {
        parent::__construct(\sprintf(
            'Expected argument of type %s, "%s" given',
            $expectedType,
            \is_object($value) ? \get_class($value) : \gettype($value)
        ));
    }
}
