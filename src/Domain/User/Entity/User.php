<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\Entity\AggregateRoot\UserChangeAliveTrait;
use App\Domain\User\Entity\AggregateRoot\UserChangeEmailTrait;
use App\Domain\User\Entity\AggregateRoot\UserCreateTrait;
use App\Domain\User\Entity\AggregateRoot\UserSignInTrait;
use App\Domain\User\ValueObj\BoolObj;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;

class User extends EventSourcedAggregateRoot
{
    // Aggregate roots
    use UserCreateTrait;
    use UserSignInTrait;
    use UserChangeEmailTrait;
    use UserChangeAliveTrait;


    /** @var UuidInterface */
    protected $uuid;

    /** @var Email */
    private $email;

    /** @var Password */
    private $password;

    /** @var BoolObj */
    protected $active;

    /** @var DateTime */
    protected $createdAt;

    /** @var DateTime|null */
    protected $updatedAt;


    /**
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }


    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }


    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }


    /**
     * @param Email $email
     */
    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }


    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }


    /**
     * @param Password $password
     */
    public function setPassword(Password $password): void
    {
        $this->password = $password;
    }


    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active->toBool();
    }


    /**
     * @param BoolObj $active
     */
    public function setActive(BoolObj $active): void
    {
        $this->active = $active;
    }


    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * @param DateTime|null $updatedAt
     */
    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
