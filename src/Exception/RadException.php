<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Exception;

class RadException extends \Exception
{
    /**
     * @param string          $message
     * @param \Throwable|null $previous
     * @param int             $code
     */
    public function __construct (string $message, \Throwable $previous = null, int $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }

}
