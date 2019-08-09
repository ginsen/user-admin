<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Service\ObtainUuidFromEmail;
use App\Domain\User\Repository\UserEventStoreInterface;

class SignInHandler implements CommandHandlerInterface
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
     * @param  SignInCommand $command
     * @throws \Exception
     */
    public function __invoke(SignInCommand $command): void
    {
        $uuid = $this->obtainUuid->get($command->email);
        $user = $this->eventStore->get($uuid);

        $user->signIn($command->plainPassword);

        $this->eventStore->store($user);
    }
}
