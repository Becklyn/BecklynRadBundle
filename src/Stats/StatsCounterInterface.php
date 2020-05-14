<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Stats;

interface StatsCounterInterface
{
    /**
     * Increments the value for the given key.
     */
    public function increment (string $key, int $amount = 1) : void;

    /**
     * Adds a DEBUG log message
     */
    public function debug (string $message) : void;

    /**
     * Adds a WARNING log message
     */
    public function warning (string $message) : void;

    /**
     * Adds a CRITICAL log message
     */
    public function critical (string $message) : void;


    /**
     * Creates a nested counter
     */
    public function createNestedCounter (string $prefix) : self;
}
