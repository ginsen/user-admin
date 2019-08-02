<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Projection;

use App\Domain\User\Event\UserWasCreated;
use App\Infrastructure\User\Repository\UserRepository;
use Broadway\ReadModel\Projector;

class UserProjectionFactory extends Projector
{
    /** @var UserRepository */
    private $repository;


    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
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
}
