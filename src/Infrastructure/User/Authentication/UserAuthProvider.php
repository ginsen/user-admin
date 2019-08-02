<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Authentication;

use App\Domain\User\Service\UserFinder;
use App\Domain\User\ValueObj\Email;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserAuthProvider implements UserProviderInterface
{
    /** @var UserFinder */
    private $userFinder;


    public function __construct(UserFinder $finderByEmail)
    {
        $this->userFinder = $finderByEmail;
    }


    public function supportsClass($class): bool
    {
        return UserAuth::class === $class;
    }


    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }


    public function loadUserByUsername($username)
    {
        $user = $this->userFinder->findByEmail(Email::fromStr($username));

        return UserAuth::create($user->getUuid(), $user->getCredentials());
    }
}
