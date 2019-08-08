<?php

declare(strict_types=1);

namespace App\Domain\User\Authentication;

use App\Domain\User\ValueObj\Credentials;
use Ramsey\Uuid\UuidInterface;

interface UserAuthenticationProviderInterface
{
    public function generateToken(UuidInterface $uuid, Credentials $credentials): string;
}
