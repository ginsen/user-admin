<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Application\Command\CommandHandlerInterface;
use App\Application\UseCase\UserCreate;
use App\Domain\User\Entity\UserInterface;

class SignUpHandler implements CommandHandlerInterface
{
    /** @var UserCreate */
    private $userCreate;


    /**
     * SignUpHandler constructor.
     * @param UserCreate $userCreate
     */
    public function __construct(UserCreate $userCreate)
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
