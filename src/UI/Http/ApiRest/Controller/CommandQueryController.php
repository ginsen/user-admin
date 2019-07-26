<?php

declare(strict_types=1);

namespace App\UI\Http\ApiRest\Controller;

use League\Tactician\CommandBus;

abstract class CommandQueryController
{
    /** @var CommandBus */
    private $commandBus;

    /** @var CommandBus */
    private $queryBus;


    /**
     * CommandQueryController constructor.
     * @param CommandBus $commandBus
     * @param CommandBus $queryBus
     */
    public function __construct(CommandBus $commandBus, CommandBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus   = $queryBus;
    }


    protected function handleCommand($command)
    {
        return $this->commandBus->handle($command);
    }


    protected function handleQuery($query)
    {
        return $this->queryBus->handle($query);
    }
}
