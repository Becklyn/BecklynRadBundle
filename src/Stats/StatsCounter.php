<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Stats;

use Symfony\Component\Console\Style\SymfonyStyle;

class StatsCounter
{
    /**
     * @var array[]
     */
    private $labels = [];


    /**
     * @var int[]
     */
    private $counts = [];


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
     *
     * @param string $key
     * @param string $label
     */
    public function setLabel (string $key, string $label, ?string $description = null) : void
    {
        $this->labels[$key] = [$label, $description];
    }


    /**
     * Increments the value for the given key.
     *
     * @param string $key
     * @param int    $amount
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
     * Renders as a table in the CLI.
     *
     * @param SymfonyStyle $io
     */
    public function render (SymfonyStyle $io) : void
    {
        $rows = \array_map(
            function (array $row)
            {
                $row[0] = "<fg=yellow>{$row[0]}</>";
                return $row;
            },
            $this->toArray()
        );

        $io->table(["Label", "×", "Description"], $rows);
    }


    /**
     * Transforms the result to a array-formed table.
     *
     * @return array
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
