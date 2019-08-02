<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\User\Exception\Credentials\InvalidCredentialsException;
use App\Domain\User\Repository\UserEventStoreInterface;
use App\Domain\User\Service\UserFinder;
use App\Domain\User\ValueObj\Email;
use Ramsey\Uuid\UuidInterface;

class SignInHandler implements CommandHandlerInterface
{
    /** @var UserFinder */
    private $userFinder;

    /** @var UserEventStoreInterface */
    private $eventStore;


    public function __construct(UserFinder $finderByEmail, UserEventStoreInterface $userEventStore)
    {
        $this->userFinder = $finderByEmail;
        $this->eventStore = $userEventStore;
    }


    /**
     * @param  SignInCommand $command
     * @throws \Exception
     */
    public function __invoke(SignInCommand $command): void
    {
        $uuid = $this->getUuidFromEmail($command->email);
        $user = $this->eventStore->get($uuid);

        $user->signIn($command->plainPassword);

        $this->eventStore->store($user);
    }


    /**
     * @param  Email         $email
     * @return UuidInterface
     */
    private function getUuidFromEmail(Email $email): UuidInterface
    {
        $userView = $this->userFinder->findByEmail($email);

        if (null === $userView) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        return $userView->getUuid();
    }
}
