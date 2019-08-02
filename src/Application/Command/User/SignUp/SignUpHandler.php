<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserEventStoreInterface;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;

class SignUpHandler implements CommandHandlerInterface
{
    /** @var UserEventStoreInterface */
    private $eventStore;

    /** @var UniqueEmailSpecificationInterface */
    private $uniqueEmailSpecification;


    /**
     * SignUpHandler constructor.
     * @param UserEventStoreInterface           $userEventStore
     * @param UniqueEmailSpecificationInterface $uniqueEmailSpecification
     */
    public function __construct(
        UserEventStoreInterface $userEventStore,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->eventStore               = $userEventStore;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }


    /**
     * @param  SignUpCommand $command
     * @return User
     */
    public function __invoke(SignUpCommand $command): User
    {
        $user = User::create(
            $command->uuid,
            $command->credentials,
            $this->uniqueEmailSpecification
        );

        $this->eventStore->store($user);

        return $user;
    }
}
