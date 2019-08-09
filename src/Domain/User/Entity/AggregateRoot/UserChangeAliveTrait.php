<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\AggregateRoot;

use App\Domain\User\Event\UserAliveChanged;
use Assert\Assertion;

trait UserChangeAliveTrait
{
    /**
     * @param bool $active
     * @throws \Exception
     */
    public function changeActive(bool $active): void
    {
        $this->apply(new UserAliveChanged($this->uuid, $active, new \DateTime()));
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
