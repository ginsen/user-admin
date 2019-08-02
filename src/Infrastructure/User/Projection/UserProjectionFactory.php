<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Projection;

use App\Domain\User\Event\UserEmailChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Service\UserFinder;
use App\Infrastructure\User\Repository\UserRepository;
use Broadway\ReadModel\Projector;

class UserProjectionFactory extends Projector
{
    /** @var UserRepository */
    private $repository;

    /** @var UserFinder */
    private $userFinder;


    public function __construct(UserRepository $repository, UserFinder $userFinder)
    {
        $this->repository = $repository;
        $this->userFinder = $userFinder;
    }


    /**
     * @param  UserWasCreated $userWasCreated
     * @throws \Exception
     */
    protected function applyUserWasCreated(UserWasCreated $userWasCreated): void
    {
        $userReadModel = UserView::fromSerializable($userWasCreated);

        $this->repository->save($userReadModel);
    }


    /**
     * @param UserEmailChanged $emailChanged
     * @throws \Exception
     */
    protected function applyUserEmailChanged(UserEmailChanged $emailChanged): void
    {
        $userReadModel = $this->userFinder->findByUuid($emailChanged->uuid);

        $userReadModel->setEmail($emailChanged->email);
        $userReadModel->setUpdatedAt($emailChanged->updatedAt);

        $this->repository->save($userReadModel);
    }
}
