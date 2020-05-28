<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Exception;

use Becklyn\RadBundle\Translation\DeferredTranslation;
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
     */
    public function setFrontendMessage ($frontendMessage) : void
    {
        if (null !== $frontendMessage && !\is_string($frontendMessage) && !$frontendMessage instanceof DeferredTranslation)
        {
            throw new UnexpectedTypeException($frontendMessage, "string, null or DeferredTranslation");
        }

        $this->frontendMessage = $frontendMessage;
    }
}
