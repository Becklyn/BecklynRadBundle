<?php declare(strict_types=1);

namespace Tests\Becklyn\Rad\Route;

use Becklyn\Rad\Entity\Interfaces\EntityInterface;
use Becklyn\Rad\Exception\InvalidRouteActionException;
use Becklyn\Rad\Exception\UnexpectedTypeException;
use Becklyn\Rad\Route\DeferredRoute;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class DeferredRouteTest extends TestCase
{
    /**
     * Tests basic generation
     */
    public function testGenerate () : void
    {
        $router = $this->getMockBuilder(RouterInterface::class)
            ->getMock();

        $router
            ->expects(self::once())
            ->method("generate")
            ->with("route_name", ["param1" => "a", "param2" => "b"], UrlGeneratorInterface::NETWORK_PATH)
            ->willReturn("result");

        $route = new DeferredRoute("route_name", ["param1" => "a", "param2" => "b"], UrlGeneratorInterface::NETWORK_PATH);
        $route->generate($router);
    }


    /**
     * @return iterable
     */
    public function provideCloneWithParameters () : iterable
    {
        yield "simple merge" => [
            ["p1" => "a", "p2" => "b"],
            ["p3" => "c"],
            ["p1" => "a", "p2" => "b", "p3" => "c"]
        ];

        yield "overwrite" => [
            ["p1" => "a", "p2" => "b"],
            ["p3" => "c", "p1" => "d"],
            ["p1" => "d", "p2" => "b", "p3" => "c"]
        ];

        yield "overwrite with null" => [
            ["p1" => "a"],
            ["p1" => null],
            ["p1" => null]
        ];
    }


    /**
     * Tests wither for parameters
     *
     * @dataProvider provideCloneWithParameters
     */
    public function testWithParameters (array $paramsBefore, array $paramsWith, array $expected) : void
    {
        $route1 = new DeferredRoute("route_name", $paramsBefore);
        $route2 = $route1->withParameters($paramsWith);

        // parameters stay unchanged
        self::assertEquals($paramsBefore, $route1->getParameters());
        // check expected result parameters
        self::assertEquals($expected, $route2->getParameters());
    }


    /**
     * Tests automatic entity interface support
     */
    public function testEntityInterfaceSupport () : void
    {
        $entity = new class implements EntityInterface
        {
            public function getId () : ?int { return 123; }
            public function isNew () : bool { return false; }
        };

        $route = new DeferredRoute("route_name", ["page" => $entity]);

        $router = $this->getMockBuilder(RouterInterface::class)
            ->getMock();

        $router
            ->expects(self::once())
            ->method("generate")
            ->with("route_name", ["page" => 123])
            ->willReturn("result");

        $route->generate($router);
    }


    /**
     * Tests that entity interfaces are also supported when using `withParameters()`
     */
    public function testEntityInterfaceSupportWhenCloning () : void
    {
        $entity1 = new class implements EntityInterface
        {
            public function getId () : ?int { return 123; }
            public function isNew () : bool { return false; }
        };

        $entity2 = new class implements EntityInterface
        {
            public function getId () : ?int { return 234; }
            public function isNew () : bool { return false; }
        };

        $route1 = new DeferredRoute("route_name", ["page" => $entity1]);

        $router = $this->getMockBuilder(RouterInterface::class)
            ->getMock();

        $router
            ->expects(self::exactly(2))
            ->method("generate")
            ->withConsecutive(
                ["route_name", ["page" => 123]],
                ["route_name", ["page" => 234]]
            )
            ->willReturn("result");

        $route1->generate($router);

        $route2 = $route1->withParameters(["page" => $entity2]);
        $route2->generate($router);
    }


    /**
     */
    public function provideValidRouteValue () : \Generator
    {
        yield ["test", "test"];
        yield [null, null];
        yield ["", ""];
        yield [
            new DeferredRoute("test"),
            "generated_route"
        ];
    }


    /**
     * @dataProvider provideValidRouteValue
     *
     * @param mixed $value
     */
    public function testValidRouteValue ($value, ?string $expected) : void
    {
        $router = $this->getMockBuilder(RouterInterface::class)
            ->getMock();

        $router
            ->method("generate")
            ->willReturn("generated_route");

        self::assertSame(
            $expected,
            DeferredRoute::generateValue($value, $router)
        );
    }


    /**
     */
    public function provideInvalidRouteValue () : \Generator
    {
        yield [true];
        yield [1];
        yield [new \stdClass()];
    }


    /**
     * @dataProvider provideInvalidRouteValue
     *
     * @param mixed $value
     */
    public function testInvalidRouteValue ($value) : void
    {
        $this->expectException(InvalidRouteActionException::class);

        $router = $this->getMockBuilder(RouterInterface::class)
            ->getMock();

        DeferredRoute::generateValue($value, $router);
    }


    /**
     */
    public function provideValueVariations () : iterable
    {
        yield [true, "test", DeferredRoute::REQUIRED];
        yield [true, "test", DeferredRoute::OPTIONAL];
        yield [true, new DeferredRoute("test"), DeferredRoute::REQUIRED];
        yield [true, new DeferredRoute("test"), DeferredRoute::OPTIONAL];
        yield [false, null, DeferredRoute::REQUIRED];
        yield [true, null, DeferredRoute::OPTIONAL];
        yield [false, 1, DeferredRoute::OPTIONAL];
        yield [false, 1, DeferredRoute::REQUIRED];
        yield [false, false, DeferredRoute::OPTIONAL];
        yield [false, false, DeferredRoute::REQUIRED];
    }


    /**
     * @dataProvider provideValueVariations
     */
    public function testIsValid (bool $expected, $value, bool $isOptional) : void
    {
        self::assertSame($expected, DeferredRoute::isValidValue($value, $isOptional));
    }


    /**
     * @dataProvider provideValueVariations
     */
    public function testEnsureValid (bool $shouldBeOk, $value, bool $isOptional) : void
    {
        if (!$shouldBeOk)
        {
            $this->expectException(UnexpectedTypeException::class);
            $this->expectErrorMessage(
                $isOptional
                ? \sprintf("string, %s or null", DeferredRoute::class)
                : \sprintf("string or %s", DeferredRoute::class)
            );
        }

        DeferredRoute::ensureValidValue($value, $isOptional);

        if ($shouldBeOk)
        {
            self::assertTrue(true);
        }
    }
}
