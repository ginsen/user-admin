<?php

declare(strict_types=1);

namespace App\Application\Command\User\DisableUser;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\User\Exception\Credentials\InvalidCredentialsException;
use App\Domain\User\Repository\UserEventStoreInterface;
use App\Domain\User\Service\UserFinder;
use App\Domain\User\ValueObj\Email;
use Ramsey\Uuid\UuidInterface;

class DisableUserHandler implements CommandHandlerInterface
{
    /** @var UserFinder */
    private $userFinder;

    /** @var UserEventStoreInterface */
    private $eventStore;


    /**
     * DisableUserHandler constructor.
     * @param UserFinder              $finderByEmail
     * @param UserEventStoreInterface $userEventStore
     */
    public function __construct(UserFinder $finderByEmail, UserEventStoreInterface $userEventStore)
    {
        $this->userFinder = $finderByEmail;
        $this->eventStore = $userEventStore;
    }


    /**
     * @param DisableUserCommand $command
     */
    public function __invoke(DisableUserCommand $command)
    {
        $uuid = $this->getUuidFromEmail($command->username);
        $user = $this->eventStore->get($uuid);
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