<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface UserEventStoreInterface
{
    public function get(UuidInterface $uuid): User;

    public function store(User $user): void;
}
