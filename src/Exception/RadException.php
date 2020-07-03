<?php declare(strict_types=1);

namespace Becklyn\Rad\Exception;

class RadException extends \Exception
{
    /**
     */
    public function __construct (string $message, ?\Throwable $previous = null, int $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }

}
