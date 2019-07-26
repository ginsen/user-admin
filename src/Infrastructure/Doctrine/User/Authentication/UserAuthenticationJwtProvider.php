<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\User\Authentication;

use App\Domain\User\ValueObj\Credentials;
use App\Infrastructure\Doctrine\User\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class UserAuthenticationJwtProvider
{
    /** @var JWTTokenManagerInterface */
    private $JWTManager;


    public function __construct(JWTTokenManagerInterface $JWTManager)
    {
        $this->JWTManager = $JWTManager;
    }


    public function generateToken(UuidInterface $uuid, Credentials $credentials): string
    {
        $user = User::create($uuid, $credentials);

        return $this->JWTManager->create($user);
    }
}
