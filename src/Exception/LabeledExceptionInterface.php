<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Exception;

use Becklyn\RadBundle\Translation\DeferredTranslation;

/**
 * Marks an exception as having a frontend label.
 *
 * Can be easily implemented using {@see LabeledExceptionTrait}.
 *
 * @final
 */
interface LabeledExceptionInterface
{
    /**
     * @return DeferredTranslation|string|null
     */
    public function getFrontendMessage ();
}
