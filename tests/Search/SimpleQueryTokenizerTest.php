<?php declare(strict_types=1);

namespace Tests\Becklyn\Rad\Search;

use Becklyn\Rad\Search\SimpleQueryTokenizer;
use PHPUnit\Framework\TestCase;

final class SimpleQueryTokenizerTest extends TestCase
{
    /**
     */
    public function provideTokenize () : iterable
    {
        yield "simple" => ["test", "test%"];
        yield "trim space" => ["  test  ", "test%"];
        yield "multi test" => ["test abc def", "test% abc% def%"];
        yield "no wildcard injection middle" => ["test%test", "test\\%test%"];
        yield "no wildcard injection start" => ["%test", "\\%test%"];
        yield "no wildcard injection end" => ["test%", "test\\%%"];
        yield "collapse multiple spaces" => ["  a   b    c  ", "a% b% c%"];
    }


    /**
     * @dataProvider provideTokenize
     */
    public function testTokenize (string $query, string $expected) : void
    {
        $tokenizer = new SimpleQueryTokenizer();
        self::assertSame($expected, $tokenizer->transformToQuery($query));
    }
}
