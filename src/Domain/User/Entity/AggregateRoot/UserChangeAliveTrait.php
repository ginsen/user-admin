<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\AggregateRoot;

use App\Domain\User\Event\UserAliveChanged;
use App\Domain\User\ValueObj\BoolObj;
use App\Domain\User\ValueObj\DateTime;
use Assert\Assertion;

trait UserChangeAliveTrait
{
    /**
     * @param BoolObj $active
     */
    public function changeActive(BoolObj $active): void
    {
        $this->apply(new UserAliveChanged($this->uuid, $active, DateTime::now()));
    }


    /**
     * @param UserAliveChanged $event
     */
    protected function applyUserAliveChanged(UserAliveChanged $event): void
    {
        Assertion::notEq($this->active, $event->active, 'This user has already same status');

        $this->setActive($event->active);
        $this->setUpdatedAt($event->updatedAt);
    }
}
