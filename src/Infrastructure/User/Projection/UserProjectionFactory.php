<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Projection;

use App\Domain\User\Event\UserAliveChanged;
use App\Domain\User\Event\UserEmailChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Repository\UserReadModelInterface;
use App\Domain\User\Service\UserFinder;
use Broadway\ReadModel\Projector;

class UserProjectionFactory extends Projector
{
    /** @var UserReadModelInterface */
    private $readModel;

    /** @var UserFinder */
    private $userFinder;


    public function __construct(UserReadModelInterface $userReadModel, UserFinder $userFinder)
    {
        $this->readModel  = $userReadModel;
        $this->userFinder = $userFinder;
    }


    /**
     * @param  UserWasCreated $event
     * @throws \Exception
     */
    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $userView = UserView::fromSerializable($event);

        $this->readModel->save($userView);
    }


    /**
     * @param  UserEmailChanged $event
     * @throws \Exception
     */
    protected function applyUserEmailChanged(UserEmailChanged $event): void
    {
        $userView = $this->userFinder->findByUuid($event->uuid);

        $userView->setEmail($event->email);
        $userView->setUpdatedAt($event->updatedAt);

        $this->readModel->update($userView);
    }


    /**
     * @param  UserAliveChanged $event
     * @throws \Exception
     */
    protected function applyUserAliveChanged(UserAliveChanged $event): void
    {
        $userView = $this->userFinder->findByUuid($event->uuid);

        $userView->setActive($event->active);
        $userView->setUpdatedAt($event->updatedAt);

        $this->readModel->update($userView);
    }
}
