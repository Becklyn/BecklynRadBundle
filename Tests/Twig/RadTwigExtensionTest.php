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

        static::assertSame("abc 123", $extension->appendToKey($array, "existing", "123")["existing"]);
        static::assertSame("123", $extension->appendToKey($array, "missing", "123")["missing"]);
    }
}
