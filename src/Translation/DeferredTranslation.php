<?php declare(strict_types=1);

namespace Becklyn\Rad\Translation;

use Becklyn\Rad\Exception\InvalidTranslationActionException;
use Becklyn\Rad\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * A deferred translation.
 *
 * Basically a VO encapsulating all of the logic required for translating a key, but without directly translating it.
 * Instead the translation can be deferred to a later point in time (this way you don't need a dependency on the
 * translator right away).
 */
class DeferredTranslation
{
    public const OPTIONAL = true;
    public const REQUIRED = false;

    private string $id;
    private array $parameters;
    private string $domain;


    /**
     * @param string $id         Translation key #TranslationKey
     * @param array  $parameters Translation parameters
     * @param string $domain     Translation domain #TranslationDomain
     */
    public function __construct (string $id, array $parameters = [], string $domain = "messages")
    {
        $this->id = $id;
        $this->parameters = $parameters;
        $this->domain = $domain;
    }


    /**
     */
    public function getId () : string
    {
        return $this->id;
    }


    /**
     */
    public function getParameters () : array
    {
        return $this->parameters;
    }


    /**
     */
    public function getDomain () : string
    {
        return $this->domain;
    }


    /**
     * @return static
     */
    public function withParameters (array $parameters) : self
    {
        $modified = clone $this;
        $modified->parameters = \array_replace($modified->parameters, $parameters);
        return $modified;
    }


    /**
     * Translates the given string
     */
    public function translate (TranslatorInterface $translator) : string
    {
        return $translator->trans(
            $this->id,
            $this->parameters,
            $this->domain
        );
    }


    /**
     * @param self|string|null $value
     */
    public static function translateValue ($value, TranslatorInterface $translator) : ?string
    {
        if (null === $value || \is_string($value))
        {
            return $value;
        }

        if ($value instanceof self)
        {
            return $value->translate($translator);
        }

        throw new InvalidTranslationActionException(\sprintf(
            "Can't translate value of type '%s', only DeferredTranslations, strings and null are allowed.",
            \is_object($value) ? \get_class($value) : \gettype($value)
        ));
    }


    /**
     * Translates all values
     *
     * @param array<self|string|mixed|null> $values
     *
     * @return array<string|null>
     */
    public static function translateAllValues (array $values, TranslatorInterface $translator) : array
    {
        $result = [];

        foreach ($values as $key => $value)
        {
            $result[$key] = self::translateValue($value, $translator);
        }

        return $result;
    }


    /**
     * Returns whether the given value is a valid translation value
     *
     * @param mixed $value
     */
    public static function isValidValue ($value, bool $allowNull = self::REQUIRED) : bool
    {
        return null !== $value
            ? (\is_string($value) || $value instanceof self)
            : $allowNull;
    }


    /**
     * Ensures that the given value is valid
     *
     * @param mixed $value
     */
    public static function ensureValidValue ($value, bool $isOptional = self::REQUIRED) : void
    {
        if (!self::isValidValue($value, $isOptional))
        {

            throw new UnexpectedTypeException(
                $value,
                $isOptional
                    ? \sprintf("string, %s or null", self::class)
                    : \sprintf("string or %s", self::class)
            );
        }
    }


    //region Named Constructors
    /**
     * Special named constructor for the domain "messages"
     */
    public static function messages (string $id, array $parameters = []) : self
    {
        return new self($id, $parameters, "messages");
    }

    /**
     * Special named constructor for the domain "backend"
     */
    public static function backend (string $id, array $parameters = []) : self
    {
        return new self($id, $parameters, "backend");
    }
    //endregion
}
