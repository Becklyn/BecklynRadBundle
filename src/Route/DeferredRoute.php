<?php declare(strict_types=1);

namespace Becklyn\Rad\Route;

use Becklyn\Rad\Entity\Interfaces\EntityInterface;
use Becklyn\Rad\Exception\InvalidRouteActionException;
use Becklyn\Rad\Exception\UnexpectedTypeException;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * A deferred route.
 *
 * Basically a VO encapsulating all of the logic required for generating a route, but without directly generating it.
 * Instead the generation can be deferred to a later point in time (this way you don't need a dependency on the router
 * right away).
 */
class DeferredRoute implements LinkableInterface
{
    public const OPTIONAL = true;
    public const REQUIRED = false;

    private string $route;
    private array $parameters;
    private int $referenceType;


    /**
     * @param string                                                    $route         The route name.  #Route
     * @param array<string, string|int|float|EntityInterface|bool|null> $parameters    the parameters required for generating the route
     * @param int                                                       $referenceType The reference type to generate for this route
     */
    public function __construct (string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $this->route = $route;
        $this->parameters = $this->normalizeParameters($parameters);
        $this->referenceType = $referenceType;
    }


    /**
     */
    public function getRoute () : string
    {
        return $this->route;
    }


    /**
     */
    public function getParameters () : array
    {
        return $this->parameters;
    }


    /**
     */
    public function getReferenceType () : int
    {
        return $this->referenceType;
    }


    /**
     * @throws RouteNotFoundException
     * @throws MissingMandatoryParametersException
     * @throws InvalidParameterException
     */
    public function generate (UrlGeneratorInterface $urlGenerator) : string
    {
        return $urlGenerator->generate($this->route, $this->parameters, $this->referenceType);
    }


    /**
     * @return static
     */
    public function withParameters (array $parameters) : self
    {
        $modified = clone $this;
        $modified->parameters = \array_replace($modified->parameters, $this->normalizeParameters($parameters));
        return $modified;
    }


    /**
     * Normalizes the parameters
     */
    private function normalizeParameters (array $parameters) : array
    {
        $normalized = [];

        foreach ($parameters as $key => $value)
        {
            $normalized[$key] = $value instanceof EntityInterface
                ? $value->getId()
                : $value;
        }

        return $normalized;
    }


    /**
     * @param self|string|null $value
     */
    public static function generateValue ($value, UrlGeneratorInterface $urlGenerator) : ?string
    {
        if (null === $value || \is_string($value))
        {
            return $value;
        }

        if ($value instanceof self)
        {
            return $value->generate($urlGenerator);
        }

        throw new InvalidRouteActionException(\sprintf(
            "Can't generate route for value of type '%s', only DeferredRoutes, strings and null are allowed.",
            \is_object($value) ? \get_class($value) : \gettype($value)
        ));
    }


    /**
     * Returns whether the given value is a valid route value
     *
     * @param mixed $value
     */
    public static function isValidValue ($value, bool $isOptional = self::REQUIRED) : bool
    {
        return null !== $value
            ? (\is_string($value) || $value instanceof self)
            : $isOptional;
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
}
