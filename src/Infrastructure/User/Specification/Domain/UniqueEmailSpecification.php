<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Specification\Domain;

use App\Domain\User\Entity\UserInterface;
use App\Domain\User\Exception\Email\EmailAlreadyExistException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\Specification\UserSpecificationFactoryInterface;
use App\Domain\User\ValueObj\Email;
use Doctrine\ORM\NonUniqueResultException;

class UniqueEmailSpecification implements UniqueEmailSpecificationInterface
{
    /** @var UserRepositoryInterface */
    private $userRepo;

    /** @var UserSpecificationFactoryInterface */
    private $specFactory;


    /**
     * UniqueEmailSpecification constructor.
     *
     * @param UserRepositoryInterface           $userRepo
     * @param UserSpecificationFactoryInterface $userSpecFactory
     */
    public function __construct(
        UserRepositoryInterface $userRepo,
        UserSpecificationFactoryInterface $userSpecFactory
    ) {
        $this->userRepo    = $userRepo;
        $this->specFactory = $userSpecFactory;
    }


    public function isUnique(Email $email): bool
    {
        return $this->isSatisfiedBy($email);
    }


    public function isSatisfiedBy($value): bool
    {
        try {
            $specification = $this->specFactory->createForFindOneWithEmail($value);
            $authUser      = $this->userRepo->getOneOrNull($specification);

            if ($authUser instanceof UserInterface) {
                throw new EmailAlreadyExistException('Email already registered.');
            }
        } catch (NonUniqueResultException $e) {
            throw new EmailAlreadyExistException('Email already registered.');
        }

        return true;
    }
}
