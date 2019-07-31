<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Domain\User\Entity\UserInterface;
use App\Domain\User\Exception\Credentials\InvalidCredentialsException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\UserFinderInterface;
use App\Domain\User\Specification\UserSpecificationFactoryInterface;
use App\Domain\User\ValueObj\Email;
use Ramsey\Uuid\UuidInterface;

class UserFinder implements UserFinderInterface
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


    public function findByEmail(Email $email): UserInterface
    {
        $specification = $this->specFactory->createForFindOneWithEmail($email);
        $user          = $this->userRepo->getOneOrNull($specification);

        if (null === $user) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        return $user;
    }


    public function findByUuid(UuidInterface $uuid): UserInterface
    {
        $specification = $this->specFactory->createForFindOneWithUuid($uuid);
        $user          = $this->userRepo->getOneOrNull($specification);

        if (null === $user) {
            throw new InvalidCredentialsException('Invalid uuid entered.');
        }

        return $user;
    }
}
