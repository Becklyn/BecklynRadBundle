<?php declare(strict_types=1);

namespace Becklyn\Rad\Command;

use Algolia\SearchBundle\EventListener\SearchIndexerSubscriber;
use Becklyn\Rad\Integration\Profiler;
use Doctrine\Common\EventManager;

class CommandHelper
{
    /** @var Profiler */
    private $profiler;

    /** @var EventManager */
    private $eventManager;


    /**
     */
    public function __construct (Profiler $profiler, EventManager $eventManager)
    {
        $this->profiler = $profiler;
        $this->eventManager = $eventManager;
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


    /**
     * Disables search indexing
     */
    public function disableSearchIndexing () : void
    {
        $remove = [];

        foreach ($this->eventManager->getListeners() as $event => $listeners)
        {
            foreach ($listeners as $listener)
            {
                if ($listener instanceof SearchIndexerSubscriber)
                {
                    $remove[$event] = $listener;
                }
            }
        }

        foreach ($remove as $event => $listener)
        {
            $this->eventManager->removeEventListener($event, $listener);
        }
    }
}
