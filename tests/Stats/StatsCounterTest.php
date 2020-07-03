<?php declare(strict_types=1);

namespace Tests\Becklyn\Rad\Stats;

use Becklyn\Rad\Stats\StatsCounter;
use PHPUnit\Framework\TestCase;

class StatsCounterTest extends TestCase
{
    public function testEmpty () : void
    {
        $stats = new StatsCounter();
        self::assertSame([], $stats->toArray());
    }


    /**
     *
     */
    public function testResettingLabels () : void
    {
        $stats = new StatsCounter();
        $stats->setLabel("test", "Test");
        $stats->setLabel("test2", "Test 2");
        $stats->increment("test");
        $stats->setLabel("test", "Test Changed");

        $result = $stats->toArray();
        self::assertCount(2, $result);
        self::assertSame("Test Changed", $result[0][0]);
        self::assertSame(1, $result[0][1]);
        self::assertSame("Test 2", $result[1][0]);
        self::assertSame(0, $result[1][1]);
    }


    /**
     *
     */
    public function testConstructorLabels () : void
    {
        $stats = new StatsCounter([
            "test" => "Test",
            "test2" => ["Test 2", "With Description"],
            "test3" => ["Test 3"],
        ]);

        $result = $stats->toArray();
        self::assertCount(3, $result);
        self::assertSame("Test", $result[0][0]);
        self::assertSame(null, $result[0][2]);
        self::assertSame("Test 2", $result[1][0]);
        self::assertSame("With Description", $result[1][2]);
        self::assertSame("Test 3", $result[2][0]);
        self::assertSame(null, $result[2][2]);
    }


    /**
     *
     */
    public function testIncrement () : void
    {
        $stats = new StatsCounter();
        $stats->increment("test");
        $stats->increment("test2");
        $stats->increment("test3");
        $stats->increment("test2", 100);

        $result = $stats->toArray();
        self::assertCount(3, $result);
        self::assertSame("Test", $result[0][0]);
        self::assertSame(1, $result[0][1]);
        self::assertSame("Test2", $result[1][0]);
        self::assertSame(101, $result[1][1]);
        self::assertSame("Test3", $result[2][0]);
        self::assertSame(1, $result[2][1]);
    }


    /**
     *
     */
    public function testAutoAddLabel () : void
    {
        $stats = new StatsCounter([
            "existing" => "Existing",
        ]);
        $stats->increment("existing");
        $stats->increment("entry_missing");

        $result = $stats->toArray();
        self::assertCount(2, $result);
        self::assertSame("Existing", $result[0][0]);
        self::assertSame(1, $result[0][1]);
        self::assertSame("Entry Missing", $result[1][0]);
        self::assertSame(1, $result[1][1]);
    }
}
