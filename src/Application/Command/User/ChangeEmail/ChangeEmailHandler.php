<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeEmail;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\User\Repository\UserEventStoreInterface;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;

class ChangeEmailHandler implements CommandHandlerInterface
{
    /** @var UserEventStoreInterface */
    private $eventStore;

    /** @var UniqueEmailSpecificationInterface */
    private $uniqueEmailSpecification;


    public function __construct(
        UserEventStoreInterface $userEventStore,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->eventStore               = $userEventStore;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }


    public function __invoke(ChangeEmailCommand $command): void
    {
        $user = $this->eventStore->get($command->uuid);

        $user->changeEmail($command->email, $this->uniqueEmailSpecification);

        $this->eventStore->store($user);
    }
}
