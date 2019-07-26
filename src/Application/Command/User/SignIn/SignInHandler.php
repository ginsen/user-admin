<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\User\Service\UserFinderInterface;

class SignInHandler implements CommandHandlerInterface
{
    /** @var UserFinderInterface */
    private $userFinder;


    public function __construct(UserFinderInterface $finderByEmail)
    {
        $this->userFinder = $finderByEmail;
    }


    public function __invoke(SignInCommand $command): void
    {
        $user = $this->userFinder->findByEmail($command->email);
        $user->signIn($command->plainPassword);
    }
}
