<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\UserInterface;
use App\Domain\User\ValueObj\Credentials;

interface UserCreateInterface
{
    public function create(Credentials $credentials): UserInterface;
}
