<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeAlive;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Service\ObtainUuidFromEmail;
use App\Domain\User\Repository\UserEventStoreInterface;

class ChangeAliveHandler implements CommandHandlerInterface
{
    /** @var ObtainUuidFromEmail */
    private $obtainUuid;

    /** @var UserEventStoreInterface */
    private $eventStore;


    public function __construct(ObtainUuidFromEmail $obtainUuidFromEmail, UserEventStoreInterface $userEventStore)
    {
        $this->obtainUuid = $obtainUuidFromEmail;
        $this->eventStore = $userEventStore;
    }


    /**
     * @param  ChangeAliveCommand $command
     * @throws \Exception
     */
    public function __invoke(ChangeAliveCommand $command)
    {
        $uuid = $this->obtainUuid->get($command->username);
        $user = $this->eventStore->get($uuid);

        $user->changeActive($command->active);
        $this->eventStore->store($user);
    }
}
