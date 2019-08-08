<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Authentication;

use App\Domain\User\Exception\Credentials\InvalidCredentialsException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class Session
{
    /** @var TokenStorageInterface */
    private $tokenStorage;


    /**
     * Session constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @param  string $uuid
     * @return bool
     */
    public function sameByUuid(string $uuid): bool
    {
        return $this->get()['uuid']->toString() === $uuid;
    }


    /**
     * @return array
     */
    public function get(): array
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            throw new InvalidCredentialsException('Invalid token');
        }

        $user = $token->getUser();

        if (!$user instanceof UserAuth) {
            throw new InvalidCredentialsException('Invalid user auth');
        }

        return [
            'uuid'     => $user->uuid(),
            'username' => $user->getUsername(),
            'roles'    => $user->getRoles(),
        ];
    }
}
