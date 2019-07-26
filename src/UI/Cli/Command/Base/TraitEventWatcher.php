<?php

declare(strict_types=1);

namespace App\UI\Cli\Command\Base;

use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

trait TraitEventWatcher
{
    /** @var string */
    private $eventWatcher;

    /** @var Stopwatch */
    private $stopWatch;


    protected function initWatcher(): void
    {
        $this->eventWatcher = uniqid('', true);
        $this->stopWatch    = new Stopwatch();
        $this->stopWatch->start($this->eventWatcher);
    }


    protected function stopWatcher(): StopwatchEvent
    {
        return $this->stopWatch->stop($this->eventWatcher);
    }
}
