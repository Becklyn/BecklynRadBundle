<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Command;

use Becklyn\RadBundle\Integration\Profiler;

class CommandHelper
{
    /** @var Profiler */
    private $profiler;


    /**
     */
    public function __construct (Profiler $profiler)
    {
        $this->profiler = $profiler;
    }


    /**
     * Helper for long running tasks
     */
    public function startLongRunningCommand () : void
    {
        \ini_set("memory_limit", "-1");
        \set_time_limit(0);
        $this->profiler->disable();
    }
}
