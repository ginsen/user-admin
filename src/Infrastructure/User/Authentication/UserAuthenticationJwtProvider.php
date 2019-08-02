<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Authentication;

use App\Domain\User\Authentication\UserAuthenticationProviderInterface;
use App\Domain\User\ValueObj\Credentials;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class UserAuthenticationJwtProvider implements UserAuthenticationProviderInterface
{
    /** @var JWTTokenManagerInterface */
    private $JWTManager;


    public function __construct(JWTTokenManagerInterface $JWTManager)
    {
        $this->JWTManager = $JWTManager;
    }


    public function generateToken(UuidInterface $uuid, Credentials $credentials): string
    {
        $userAuth = UserAuth::create($uuid, $credentials);

        return $this->JWTManager->create($userAuth);
    }
}
