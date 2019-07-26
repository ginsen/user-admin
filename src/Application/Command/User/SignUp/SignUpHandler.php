<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\User\Entity\UserInterface;
use App\Domain\User\Service\UserCreateInterface;

class SignUpHandler implements CommandHandlerInterface
{
    /** @var UserCreateInterface */
    private $userCreate;


    /**
     * SignUpHandler constructor.
     * @param UserCreateInterface $userCreate
     */
    public function __construct(UserCreateInterface $userCreate)
    {
        $this->userCreate = $userCreate;
    }


    /**
     * @param  SignUpCommand $command
     * @throws \Exception
     * @return UserInterface
     */
    public function __invoke(SignUpCommand $command): UserInterface
    {
        return $this->userCreate->create($command->credentials);
    }
}
