<?php declare(strict_types=1);

namespace Becklyn\Rad\Stats;

use Symfony\Component\Console\Style\SymfonyStyle;

class StatsCounter implements StatsCounterInterface
{
    /** @var array[] */
    private array $labels = [];

    /** @var int[] */
    private array $counts = [];

    /** @var string[] */
    private array $debug = [];

    /** @var string[] */
    private array $warnings = [];

    /** @var string[] */
    private array $critical = [];


    /**
     * Instantiates a new import logger.
     *
     * @param (array|string)[] $labels
     */
    public function __construct (array $labels = [])
    {
        foreach ($labels as $key => $label)
        {
            if (\is_string($label))
            {
                $this->setLabel($key, $label);
            }
            elseif (\is_array($label))
            {
                $this->setLabel($key, $label[0] ?? "", $label[1] ?? null);
            }
        }
    }


    /**
     * Sets a label.
     */
    public function setLabel (string $key, string $label, ?string $description = null) : void
    {
        $this->labels[$key] = [$label, $description];
    }


    /**
     * @inheritDoc
     */
    public function increment (string $key, int $amount = 1) : void
    {
        if (!isset($this->counts[$key]))
        {
            $this->counts[$key] = 0;
        }

        $this->counts[$key] += $amount;
    }


    /**
     * @inheritDoc
     */
    public function debug (string $message) : void
    {
        $this->debug[] = $message;
    }


    /**
     * @inheritDoc
     */
    public function warning (string $message) : void
    {
        $this->warnings[] = $message;
    }


    /**
     * @inheritDoc
     */
    public function critical (string $message) : void
    {
        $this->critical[] = $message;
    }


    /**
     * @inheritDoc
     */
    public function createNestedCounter (string $prefix) : StatsCounterInterface
    {
        return new NestedStatsCounter($this, $prefix);
    }


    /**
     * Renders as a table in the CLI.
     *
     * @param bool $showDebug decides whether the debug log will be displayed. If `null` it will be shown in
     *                        verbose (`-v`) mode
     */
    public function render (SymfonyStyle $io, ?bool $showDebug = null) : void
    {
        if (null === $showDebug)
        {
            $showDebug = $io->isVerbose();
        }

        $rows = \array_map(
            function (array $row)
            {
                $row[0] = "<fg=yellow>{$row[0]}</>";
                return $row;
            },
            $this->toArray()
        );

        $io->table(["Label", "×", "Description"], $rows);

        $listing = [];

        foreach ($this->critical as $line)
        {
            $listing[] = "<fg=red>CRITICAL</> {$line}";
        }

        foreach ($this->warnings as $line)
        {
            $listing[] = "<fg=yellow>WARNING</> {$line}";
        }

        if ($showDebug)
        {
            foreach ($this->debug as $line)
            {
                $listing[] = "<fg=blue>DEBUG</> {$line}";
            }
        }

        if (!empty($listing))
        {
            $io->newLine();
            $io->listing($listing);
        }
    }


    /**
     * Transforms the result to a array-formed table.
     */
    public function toArray () : array
    {
        $headers = [];

        foreach ($this->labels as $key => $label)
        {
            $headers[$key] = $label;
        }

        foreach ($this->counts as $key => $count)
        {
            if (!\array_key_exists($key, $headers))
            {
                // automatically generate label
                $headers[$key] = [\ucwords(\str_replace("_", " ", $key))];
            }
        }

        // now all possible headers are set, so build the rows
        $rows = [];

        foreach ($headers as $key => $headerConfig)
        {
            $rows[] = [
                $headerConfig[0],
                $this->counts[$key] ?? 0,
                $headerConfig[1] ?? null,
            ];
        }

        return $rows;
    }
}
