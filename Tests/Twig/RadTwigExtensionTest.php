<?php declare(strict_types=1);

namespace Becklyn\RadBundle\tests\Twig;

use Becklyn\RadBundle\Twig\RadTwigExtension;
use PHPUnit\Framework\TestCase;

class RadTwigExtensionTest extends TestCase
{
    public function testAppendToKey ()
    {
        $extension = new RadTwigExtension();
        $array = [
            "existing" => "abc",
        ];

        self::assertSame("abc 123", $extension->appendToKey($array, "existing", "123")["existing"]);
        self::assertSame("123", $extension->appendToKey($array, "missing", "123")["missing"]);
    }
}
