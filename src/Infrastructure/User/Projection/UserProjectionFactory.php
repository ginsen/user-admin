<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Projection;

use App\Domain\User\Event\UserEmailChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Event\UserAliveChanged;
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

        $this->repository->save($userReadModel);
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

        $this->repository->save($userReadModel);
    }
}
