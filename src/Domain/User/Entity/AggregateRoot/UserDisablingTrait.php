<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\AggregateRoot;

use App\Domain\User\Event\UserWasDisabled;
use Assert\Assertion;

trait UserDisablingTrait
{
    /**
     * @throws \Exception
     */
    public function disable(): void
    {
        $active = false;
        $this->apply(new UserWasDisabled($this->uuid, $active, new \DateTime()));
    }


    /**
     * @param UserWasDisabled $event
     */
    protected function applyUserWasDisabled(UserWasDisabled $event): void
    {
        Assertion::notEq($this->active, $event->active, 'This user is already disabled');

        $this->setActive($event->active);
        $this->setUpdatedAt($event->updatedAt);
    }
}
