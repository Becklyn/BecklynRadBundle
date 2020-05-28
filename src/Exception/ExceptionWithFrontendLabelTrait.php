<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Exception;

use Becklyn\RadBundle\Translation\DeferredTranslation;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * @final
 */
trait ExceptionWithFrontendLabelTrait
{
    /** @var string|DeferredTranslation|null */
    private $frontendLabel;


    /**
     * @return DeferredTranslation|string|null
     */
    public function getFrontendLabel ()
    {
        return $this->frontendLabel;
    }


    /**
     * @param DeferredTranslation|string|null $frontendLabel
     */
    public function setFrontendLabel ($frontendLabel) : void
    {
        if (null !== $frontendLabel && !\is_string($frontendLabel) && !$frontendLabel instanceof DeferredTranslation)
        {
            throw new UnexpectedTypeException($frontendLabel, "string, null or DeferredTranslation");
        }

        $this->frontendLabel = $frontendLabel;
    }
}
