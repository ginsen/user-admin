<?php

declare(strict_types=1);

namespace App\Application\Command\User\DisableUser;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Service\ObtainUuidFromEmail;
use App\Domain\User\Repository\UserEventStoreInterface;

class DisableUserHandler implements CommandHandlerInterface
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
     * @param  DisableUserCommand $command
     * @throws \Exception
     */
    public function __invoke(DisableUserCommand $command)
    {
        $uuid = $this->obtainUuid->get($command->username);
        $user = $this->eventStore->get($uuid);

        $user->disable();
        $this->eventStore->store($user);
    }
}
