<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Exception;

/**
 * Like {@see EntityRemovalBlockedException}, except that this exception carries a frontend message.
 */
class LabeledEntityRemovalBlockedException extends EntityRemovalBlockedException implements LabeledExceptionInterface
{
    /** @var string */
    private $frontendMessage;

    /**
     * {@inheritdoc}
     *
     * The $frontendMessage must be a message key from the default message domain that can be translated to the user message.
     *
     * @param object|object[] $entities
     * @param string          $frontendMessage the #TranslationKey to use
     */
    public function __construct ($entities, string $message, string $frontendMessage, ?\Throwable $previous = null)
    {
        parent::__construct($entities, $message, $previous);
        $this->frontendMessage = $frontendMessage;
    }

    /**
     */
    public function getFrontendMessage ()
    {
        return $this->frontendMessage;
    }
}
