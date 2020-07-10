<?php declare(strict_types=1);

namespace Becklyn\Rad\Stats;

final class NestedStatsCounter implements StatsCounterInterface
{
    private StatsCounterInterface $base;
    private string $prefix;

    /**
     */
    public function __construct (StatsCounterInterface $base, string $prefix)
    {
        $this->base = $base;
        $this->prefix = \rtrim($prefix) . " ";
    }


    /**
     * @inheritDoc
     */
    public function increment (string $key, int $amount = 1) : void
    {
        $this->base->increment($key, $amount);
    }


    /**
     * @inheritDoc
     */
    public function debug (string $message) : void
    {
        $this->base->debug($this->prefix . $message);
    }


    /**
     * @inheritDoc
     */
    public function warning (string $message) : void
    {
        $this->base->warning($this->prefix . $message);
    }


    /**
     * @inheritDoc
     */
    public function critical (string $message) : void
    {
        $this->base->critical($this->prefix . $message);
    }


    /**
     * @inheritDoc
     */
    public function createNestedCounter (string $prefix) : StatsCounterInterface
    {
        return new self($this, $prefix);
    }
}
