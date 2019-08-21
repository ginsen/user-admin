<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\UserViewInterface;
use App\Domain\User\Exception\Credentials\InvalidCredentialsException;
use App\Domain\User\Repository\UserReadModelInterface;
use App\Domain\User\Specification\UserSpecificationFactoryInterface;
use App\Domain\User\ValueObj\Email;
use Ramsey\Uuid\UuidInterface;

class UserFinder
{
    /** @var UserReadModelInterface */
    private $readModel;

    /** @var UserSpecificationFactoryInterface */
    private $specFactory;


    public function __construct(
        UserReadModelInterface $userReadModel,
        UserSpecificationFactoryInterface $userSpecFactory
    ) {
        $this->readModel   = $userReadModel;
        $this->specFactory = $userSpecFactory;
    }


    /**
     * @param  Email                  $email
     * @return UserViewInterface|null
     */
    public function findByEmail(Email $email): ?UserViewInterface
    {
        $specification = $this->specFactory->createForFindOneWithEmail($email);
        $userView      = $this->readModel->getOneOrNull($specification);

        return $userView;
    }


    /**
     * @param  UuidInterface     $uuid
     * @throws \Exception
     * @return UserViewInterface
     */
    public function findByUuid(UuidInterface $uuid): UserViewInterface
    {
        $specification = $this->specFactory->createForFindOneWithUuid($uuid);
        $userView      = $this->readModel->getOneOrNull($specification);

        if (null === $userView) {
            throw new InvalidCredentialsException('Invalid uuid entered.');
        }

        return $userView;
    }
}
