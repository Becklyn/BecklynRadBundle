<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Exception;



class AutoConfigurationFailedException extends \DomainException
{
    /**
     * @inheritDoc
     */
    public function __construct (string $message, \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
