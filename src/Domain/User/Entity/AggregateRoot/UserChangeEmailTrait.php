<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\AggregateRoot;

use App\Domain\User\Event\UserEmailChanged;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use Assert\Assertion;

/**
 * @property Email $email
 */
trait UserChangeEmailTrait
{
    public function changeEmail(
        Email $email,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): void {
        $uniqueEmailSpecification->isUnique($email);

        $this->apply(new UserEmailChanged($this->uuid, $email, DateTime::now()));
    }


    /**
     * @param UserEmailChanged $event
     */
    protected function applyUserEmailChanged(UserEmailChanged $event): void
    {
        Assertion::notEq($this->email->toStr(), $event->email->toStr(), 'New email should be different');

        $this->setEmail($event->email);
        $this->setUpdatedAt($event->updatedAt);
    }
}
