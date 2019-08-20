<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Specification\Domain;

use App\Domain\User\Exception\Email\EmailAlreadyExistException;
use App\Domain\User\Repository\UserReadModelInterface;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\Specification\UserSpecificationFactoryInterface;
use App\Domain\User\ValueObj\Email;
use App\Infrastructure\User\Projection\UserView;
use Doctrine\ORM\NonUniqueResultException;

class UniqueEmailSpecification implements UniqueEmailSpecificationInterface
{
    /** @var UserReadModelInterface */
    private $readModel;

    /** @var UserSpecificationFactoryInterface */
    private $specFactory;


    /**
     * UniqueEmailSpecification constructor.
     *
     * @param UserReadModelInterface            $userReadModel
     * @param UserSpecificationFactoryInterface $userSpecFactory
     */
    public function __construct(
        UserReadModelInterface $userReadModel,
        UserSpecificationFactoryInterface $userSpecFactory
    ) {
        $this->readModel   = $userReadModel;
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
            $userView      = $this->readModel->getOneOrNull($specification);

            if ($userView instanceof UserView) {
                throw new EmailAlreadyExistException('Email already registered.');
            }
        } catch (NonUniqueResultException $e) {
            throw new EmailAlreadyExistException('Email already registered.');
        }

        return true;
    }
}
