<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Route;

use Becklyn\RadBundle\Entity\Interfaces\EntityInterface;
use Becklyn\RadBundle\Route\DeferredRoute;
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
        };

        $entity2 = new class implements EntityInterface
        {
            public function getId () : ?int { return 234; }
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
}
