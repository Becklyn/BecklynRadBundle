<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Path;

use Becklyn\RadBundle\Path\PathHelper;
use PHPUnit\Framework\TestCase;

class PathHelperTest extends TestCase
{
    /**
     * @return array
     */
    public function providerVariations ()
    {
        return [
            [["a", "b", "c"], "a/b/c"],
            [["/a/", "/b/", "/c/"], "/a/b/c/"],

            // keep leading slash untouched
            [["a", "b", "c"], "a/b/c"],
            [["/a", "b", "c"], "/a/b/c"],

            // keep trailing slash untouched
            [["a", "b", "c"], "a/b/c"],
            [["a", "b", "c/"], "a/b/c/"],
        ];
    }


    /**
     * @dataProvider providerVariations
     */
    public function testVariations (array $segments, string $expected) : void
    {
        $actual = PathHelper::join(...$segments);
        self::assertSame($expected, $actual);
    }
}
