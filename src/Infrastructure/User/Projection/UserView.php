<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Projection;

use App\Domain\User\Entity\UserViewInterface;
use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserView implements UserViewInterface
{
    /** @var UuidInterface */
    protected $uuid;

    /** @var Credentials */
    protected $credentials;

    /** @var bool */
    protected $active;

    /** @var \DateTime */
    protected $createdAt;

    /** @var \DateTime|null */
    protected $updatedAt;


    /**
     * @return string
     */
    public function getId(): string
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
     * @return Credentials
     */
    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }


    /**
     * @param Credentials $credentials
     */
    public function setCredentials(Credentials $credentials): void
    {
        $this->credentials = $credentials;
    }


    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->credentials->email;
    }


    /**
     * @param Email $email
     */
    public function setEmail(Email $email): void
    {
        $this->credentials->email = $email;
    }


    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->credentials->password;
    }


    /**
     * @param Password $password
     */
    public function setPassword(Password $password): void
    {
        $this->credentials->password = $password;
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


    /**
     * @param  Serializable $event
     * @throws \Exception
     * @return UserView
     */
    public static function fromSerializable(Serializable $event): self
    {
        return self::deserialize($event->serialize());
    }


    /**
     * @param  array      $data
     * @throws \Exception
     * @return UserView
     */
    public static function deserialize(array $data): self
    {
        $instance = new self();

        $instance->uuid        = Uuid::fromString($data['uuid']);
        $instance->credentials = new Credentials(
            Email::fromStr($data['credentials']['email']),
            Password::fromHash($data['credentials']['password'] ?? '')
        );

        $instance->active    = (bool) $data['active'];
        $instance->createdAt = new \DateTime($data['created_at']);
        $instance->updatedAt = isset($data['updated_at']) ? new \DateTime($data['updated_at']) : null;

        return $instance;
    }


    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'uuid'        => $this->getId(),
            'credentials' => [
                'email' => (string) $this->credentials->email,
            ],
            'active' => ($this->active) ? 'true' : 'false',
        ];
    }
}
