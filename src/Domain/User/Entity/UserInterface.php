<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use Ramsey\Uuid\UuidInterface;

interface UserInterface
{
    public function getUuid(): UuidInterface;

    public function getId(): string;

    public function getUsername(): string;

    public function getPassword(): string;

    public function isActive(): bool;

    public function signIn(string $plainPassword): void;
}
