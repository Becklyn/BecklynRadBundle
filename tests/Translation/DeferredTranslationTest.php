<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Translation;

use Becklyn\RadBundle\Exception\InvalidTranslationActionException;
use Becklyn\RadBundle\Translation\DeferredTranslation;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class DeferredTranslationTest extends TestCase
{
    /**
     * Tests basic generation
     */
    public function testTranslate () : void
    {
        $translator = $this->getMockBuilder(TranslatorInterface::class)
            ->getMock();

        $translator
            ->expects(self::once())
            ->method("trans")
            ->with("trans_key", ["param1" => "a", "param2" => "b"], "my_domain")
            ->willReturn("result");

        $translation = new DeferredTranslation("trans_key", ["param1" => "a", "param2" => "b"], "my_domain");
        $translation->translate($translator);
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
        $trans1 = new DeferredTranslation("trans_key", $paramsBefore);
        $trans2 = $trans1->withParameters($paramsWith);

        // parameters stay unchanged
        self::assertEquals($paramsBefore, $trans1->getParameters());
        // check expected result parameters
        self::assertEquals($expected, $trans2->getParameters());
    }


    /**
     */
    public function provideValidTranslateValue () : \Generator
    {
        yield ["test", "test"];
        yield [null, ""];
        yield [
            new DeferredTranslation("test"),
            "translated_key"
        ];
    }


    /**
     * @dataProvider provideValidTranslateValue
     *
     * @param mixed $value
     */
    public function testValidTranslateValue ($value, string $expected) : void
    {
        $translator = $this->getMockBuilder(TranslatorInterface::class)
            ->getMock();

        $translator
            ->method("trans")
            ->willReturn("translated_key");

        self::assertSame(
            $expected,
            DeferredTranslation::translateValue($value, $translator)
        );
    }


    /**
     */
    public function provideInvalidTranslateValue () : \Generator
    {
        yield [true];
        yield [1];
        yield [new \stdClass()];
    }


    /**
     * @dataProvider provideInvalidTranslateValue
     *
     * @param mixed $value
     */
    public function testInvalidTranslateValue ($value) : void
    {
        $this->expectException(InvalidTranslationActionException::class);

        $translator = $this->getMockBuilder(TranslatorInterface::class)
            ->getMock();

        DeferredTranslation::translateValue($value, $translator);
    }
}
