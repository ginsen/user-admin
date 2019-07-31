<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Ramsey\Uuid\UuidInterface;

interface UserInterface
{
    public function getUuid(): UuidInterface;

    public function getId(): string;

    public function getCredentials(): Credentials;

    public function isActive(): bool;

    public function signIn(string $plainPassword): void;

    public function getUsername(): string;

    public function setUserName(Email $email): self;

    public function getPassword(): string;

    public function setPassword(Password $password): self;

    public function getCreatedAt(): \DateTime;

    public function setCreatedAt(\DateTime $createdAt): self;

    public function getUpdatedAt(): ?\DateTime;

    public function setUpdatedAt(\DateTime $updatedAt): self;
}
