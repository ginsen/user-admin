<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\User\Entity;

use App\Domain\User\Entity\UserInterface;
use App\Domain\User\Exception\Credentials\InvalidCredentialsException;
use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

class User implements SecurityUserInterface, UserInterface
{
    /** @var UuidInterface */
    private $uuid;

    /** @var Credentials */
    private $credentials;

    /** @var bool */
    private $active;

    /** @var \DateTime */
    private $createdAt;


    private function __construct(UuidInterface $uuid, Credentials $credentials)
    {
        $this->uuid        = $uuid;
        $this->credentials = $credentials;
        $this->active      = true;
        $this->createdAt   = new \DateTime();
    }


    public static function create(UuidInterface $uuid, Credentials $credentials): self
    {
        return new self($uuid, $credentials);
    }


    public function getId(): string
    {
        return (string) $this->uuid;
    }


    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }


    public function setUserName(Email $email): self
    {
        $this->credentials->email = $email;

        return $this;
    }


    public function getUsername(): string
    {
        return $this->credentials->email->toStr();
    }


    public function getPassword(): string
    {
        return $this->credentials->password->toStr();
    }


    public function setPassword(Password $password): self
    {
        $this->credentials->password = $password;

        return $this;
    }


    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }


    public function isActive(): bool
    {
        return $this->active;
    }


    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function signIn(string $plainPassword): void
    {
        if (!$this->active) {
            throw new InvalidCredentialsException('User inactive.');
        }

        $match = $this->credentials->password->match($plainPassword);

        if (!$match) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }
    }


    public function getRoles(): array
    {
        return [
            'ROLE_USER',
        ];
    }


    public function getSalt(): void
    {
    }


    public function eraseCredentials(): void
    {
    }


    public function getEncoderName(): string
    {
        return 'bcrypt';
    }
}
