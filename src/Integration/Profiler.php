<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Integration;

use Symfony\Component\HttpKernel\Profiler\Profiler as SymfonyProfiler;

class Profiler
{
    /**
     * @var SymfonyProfiler|null
     */
    private $profiler;


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
