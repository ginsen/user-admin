<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Domain\User\Entity\UserInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\UserCreateInterface;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\ValueObj\Credentials;
use App\Infrastructure\Doctrine\User\Entity\User;
use Ramsey\Uuid\Uuid;

class UserCreate implements UserCreateInterface
{
    /** @var UserRepositoryInterface */
    private $userRepo;

    /** @var UniqueEmailSpecificationInterface */
    private $userMailSpec;


    public function __construct(
        UserRepositoryInterface $userRepo,
        UniqueEmailSpecificationInterface $userMailSpec
    ) {
        $this->userRepo     = $userRepo;
        $this->userMailSpec = $userMailSpec;
    }


    /**
     * @param  Credentials   $credentials
     * @throws \Exception
     * @return UserInterface
     */
    public function create(Credentials $credentials): UserInterface
    {
        $this->userMailSpec->isUnique($credentials->email);

        $user = User::create(Uuid::uuid4(), $credentials);

        $this->userRepo->save($user);

        return $user;
    }
}
