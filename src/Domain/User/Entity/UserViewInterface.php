<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\ValueObj\BoolObj;
use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\DateTime;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Broadway\ReadModel\SerializableReadModel;
use Ramsey\Uuid\UuidInterface;

interface UserViewInterface extends SerializableReadModel
{
    public function getUuid(): UuidInterface;

    public function getCredentials(): Credentials;

    public function setCredentials(Credentials $credentials): void;

    public function getEmail(): Email;

    public function setEmail(Email $email): void;

    public function getPassword(): Password;

    public function setPassword(Password $password): void;

    public function isActive(): bool;

    public function setActive(BoolObj $active): void;

    public function getCreatedAt(): DateTime;

    public function setCreatedAt(DateTime $createdAt): void;

    public function getUpdatedAt(): ?DateTime;

    public function setUpdatedAt(?DateTime $updatedAt): void;
}
