<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Specification\Domain;

use App\Domain\User\Exception\Email\EmailAlreadyExistException;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\Specification\UserSpecificationFactoryInterface;
use App\Domain\User\ValueObj\Email;
use App\Infrastructure\User\Projection\UserView;
use App\Infrastructure\User\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;

class UniqueEmailSpecification implements UniqueEmailSpecificationInterface
{
    /** @var UserRepository */
    private $userRepo;

    /** @var UserSpecificationFactoryInterface */
    private $specFactory;


    /**
     * UniqueEmailSpecification constructor.
     *
     * @param UserRepository                    $userRepo
     * @param UserSpecificationFactoryInterface $userSpecFactory
     */
    public function __construct(
        UserRepository $userRepo,
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

            if ($authUser instanceof UserView) {
                throw new EmailAlreadyExistException('Email already registered.');
            }
        } catch (NonUniqueResultException $e) {
            throw new EmailAlreadyExistException('Email already registered.');
        }

        return true;
    }
}
