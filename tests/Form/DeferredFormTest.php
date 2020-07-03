<?php declare(strict_types=1);

namespace Tests\Becklyn\Rad\Form;

use Becklyn\Rad\Form\DeferredForm;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

final class DeferredFormTest extends TestCase
{

    /**
     * Tests basic generation
     */
    public function testGenerate () : void
    {
        $factory = $this->getMockBuilder(FormFactoryInterface::class)
            ->getMock();

        $form = $this->getMockBuilder(FormInterface::class)
            ->getMock();

        $factory
            ->expects(self::once())
            ->method("create")
            ->with("test", ["data" => "ohai"], ["param1" => "a", "param2" => "b"])
            ->willReturn($form);

        $deferred = new DeferredForm("test", ["param1" => "a", "param2" => "b"]);
        $deferred->createForm($factory, ["data" => "ohai"]);
    }


    /**
     * @return iterable
     */
    public function provideCloneWithOptions () : iterable
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
     * Tests wither for options
     *
     * @dataProvider provideCloneWithOptions
     */
    public function testWithOptions (array $optionsBefore, array $optionsWith, array $expected) : void
    {
        $form1 = new DeferredForm("form", $optionsBefore);
        $form2 = $form1->withOptions($optionsWith);

        // parameters stay unchanged
        self::assertEquals($optionsBefore, $form1->getOptions());
        // check expected result parameters
        self::assertEquals($expected, $form2->getOptions());
    }
}
