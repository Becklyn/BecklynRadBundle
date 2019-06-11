<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Twig;

use Becklyn\RadBundle\Twig\RadTwigExtension;
use PHPUnit\Framework\TestCase;

class RadTwigExtensionTest extends TestCase
{
    public function testAppendToKey () : void
    {
        $extension = new RadTwigExtension();
        $array = [
            "existing" => "abc",
        ];

        self::assertSame("abc 123", $extension->appendToKey($array, "existing", "123")["existing"]);
        self::assertSame("123", $extension->appendToKey($array, "missing", "123")["missing"]);
    }


    /**
     * @return array
     */
    public function provideClassnames () : array
    {
        return [
            "simple" => [
                ["a" => true, "b" => true],
                "a b",
            ],
            "numbers" => [
                ["zero" => 0, "one" => 1, "two" => 2],
                "one two",
            ],
            "bool" => [
                ["true" => true, "false" => false, "null" => null],
                "true",
            ],
            "empty" => [
                [],
                "",
            ],
            "keyless values" => [
                ["a" => true, "b", "c" => false, "d", "e" => true],
                "a b d e",
            ],
        ];
    }


    /**
     * @dataProvider provideClassnames
     *
     * @param array  $classnames
     * @param string $expected
     */
    public function testClassnames (array $classnames, string $expected) : void
    {
        $extension = new RadTwigExtension();
        self::assertSame($extension->formatClassNames($classnames), $expected);
    }


    /**
     * 
     */
    public function testDataContainer () : void
    {
        $extension = new RadTwigExtension();

        self::assertSame(
            '<script class="_data-container test" type="application/json">{"&lt;b&gt;":2}</script>',
            $extension->renderDataContainer(["<b>" => 2], "test")
        );
    }
}
