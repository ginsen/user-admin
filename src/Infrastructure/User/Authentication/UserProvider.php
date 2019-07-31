<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Authentication;

use App\Domain\User\Service\UserFinderInterface;
use App\Domain\User\ValueObj\Email;
use App\Infrastructure\User\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /** @var UserFinderInterface */
    private $userFinder;


    public function __construct(UserFinderInterface $finderByEmail)
    {
        $this->userFinder = $finderByEmail;
    }


    public function supportsClass($class): bool
    {
        return User::class === $class;
    }


    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }


    public function loadUserByUsername($username)
    {
        $user = $this->userFinder->findByEmail(Email::fromStr($username));

        return User::create($user->getUuid(), $user->getCredentials());
    }
}
