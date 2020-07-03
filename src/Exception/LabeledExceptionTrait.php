<?php declare(strict_types=1);

namespace Becklyn\Rad\Exception;

use Becklyn\Rad\Translation\DeferredTranslation;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Default implementation to the {@see LabeledExceptionInterface}.
 *
 * @final
 */
trait LabeledExceptionTrait
{
    /** @var string|DeferredTranslation|null */
    private $frontendMessage;


    /**
     * @return DeferredTranslation|string|null
     */
    public function getFrontendMessage ()
    {
        return $this->frontendMessage;
    }


    /**
     * @param DeferredTranslation|string|null $frontendMessage
     *
     * @return $this
     */
    public function setFrontendMessage ($frontendMessage) : self
    {
        if (null !== $frontendMessage && !\is_string($frontendMessage) && !$frontendMessage instanceof DeferredTranslation)
        {
            throw new UnexpectedTypeException($frontendMessage, "string, null or DeferredTranslation");
        }

        $this->frontendMessage = $frontendMessage;
        return $this;
    }


    /**
     * Creates a new exception with a frontend label
     */
    public static function createWithLabel (string $exceptionMessage, $frontendMessage) : self
    {
        return (new self($exceptionMessage))
            ->setFrontendMessage($frontendMessage);
    }
}
