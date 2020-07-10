<?php declare(strict_types=1);

namespace Becklyn\Rad\Integration;

use Symfony\Component\HttpKernel\Profiler\Profiler as SymfonyProfiler;

class Profiler
{
    private ?SymfonyProfiler $profiler;


    public function __construct (?SymfonyProfiler $profiler)
    {
        $this->profiler = $profiler;
    }


    /**
     *
     */
    public function disable () : void
    {
        if (null !== $this->profiler)
        {
            $this->profiler->disable();
        }
    }
}
