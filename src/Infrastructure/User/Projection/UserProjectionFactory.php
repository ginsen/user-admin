<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Projection;

use App\Domain\User\Event\UserAliveChanged;
use App\Domain\User\Event\UserEmailChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\UserFinder;
use Broadway\ReadModel\Projector;

class UserProjectionFactory extends Projector
{
    /** @var UserRepositoryInterface */
    private $repository;

    /** @var UserFinder */
    private $userFinder;


    public function __construct(UserRepositoryInterface $userRepo, UserFinder $userFinder)
    {
        $this->repository = $userRepo;
        $this->userFinder = $userFinder;
    }


    /**
     * @param  UserWasCreated $event
     * @throws \Exception
     */
    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $userReadModel = UserView::fromSerializable($event);

        $this->repository->save($userReadModel);
    }


    /**
     * @param  UserEmailChanged $event
     * @throws \Exception
     */
    protected function applyUserEmailChanged(UserEmailChanged $event): void
    {
        $userReadModel = $this->userFinder->findByUuid($event->uuid);

        $userReadModel->setEmail($event->email);
        $userReadModel->setUpdatedAt($event->updatedAt);

        $this->repository->update($userReadModel);
    }


    /**
     * @param  UserAliveChanged $event
     * @throws \Exception
     */
    protected function applyUserAliveChanged(UserAliveChanged $event): void
    {
        $userReadModel = $this->userFinder->findByUuid($event->uuid);

        $userReadModel->setActive($event->active);
        $userReadModel->setUpdatedAt($event->updatedAt);

        $this->repository->update($userReadModel);
    }
}
