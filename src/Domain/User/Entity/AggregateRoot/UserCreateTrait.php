<?php

declare(strict_types=1);

namespace App\Domain\User\Entity\AggregateRoot;

use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\ValueObj\Credentials;
use Ramsey\Uuid\UuidInterface;

trait UserCreateTrait
{
    public static function create(
        UuidInterface $uuid,
        Credentials $credentials,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): self {
        $uniqueEmailSpecification->isUnique($credentials->email);

        $active    = true;
        $createdAt = new \DateTime();

        $user = new self();
        $user->apply(new UserWasCreated($uuid, $credentials, $active, $createdAt));

        return $user;
    }


    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->uuid = $event->uuid;

        $this->setEmail($event->credentials->email);
        $this->setPassword($event->credentials->password);
        $this->setActive($event->active);
        $this->setCreatedAt($event->createdAt);
    }
}
