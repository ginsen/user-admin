<?php

declare(strict_types=1);

namespace App\Domain\User\AggregateRoot;

use App\Domain\User\Event\UserSignedIn;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Exception\Credentials\InvalidCredentialsException;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;

class User extends EventSourcedAggregateRoot
{
    /** @var UuidInterface */
    protected $uuid;

    /** @var Email */
    private $email;

    /** @var Password */
    private $password;

    /** @var bool */
    protected $active;

    /** @var \DateTime */
    protected $createdAt;

    /** @var \DateTime|null */
    protected $updatedAt;


    /**
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }


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


    public function signIn(string $plainPassword): void
    {
        $match = $this->getPassword()->match($plainPassword);

        if (!$match) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        $this->apply(new UserSignedIn($this->uuid, $this->email));
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
        return $this->active;
    }


    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }


    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
