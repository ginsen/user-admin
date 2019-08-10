<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\UserViewInterface;
use App\Domain\User\Exception\Credentials\InvalidCredentialsException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\UserSpecificationFactoryInterface;
use App\Domain\User\ValueObj\Email;
use Ramsey\Uuid\UuidInterface;

class UserFinder
{
    /** @var UserRepositoryInterface */
    private $userRepo;

    /** @var UserSpecificationFactoryInterface */
    private $specFactory;


    public function __construct(
        UserRepositoryInterface $userRepo,
        UserSpecificationFactoryInterface $specFactory
    ) {
        $this->userRepo    = $userRepo;
        $this->specFactory = $specFactory;
    }


    /**
     * @param  Email                  $email
     * @return UserViewInterface|null
     */
    public function findByEmail(Email $email): ?UserViewInterface
    {
        $specification = $this->specFactory->createForFindOneWithEmail($email);
        $user          = $this->userRepo->getOneOrNull($specification);

        return $user;
    }


    /**
     * @param  UuidInterface     $uuid
     * @throws \Exception
     * @return UserViewInterface
     */
    public function findByUuid(UuidInterface $uuid): UserViewInterface
    {
        $specification = $this->specFactory->createForFindOneWithUuid($uuid);
        $user          = $this->userRepo->getOneOrNull($specification);

        if (null === $user) {
            throw new InvalidCredentialsException('Invalid uuid entered.');
        }

        return $user;
    }
}
