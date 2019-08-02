<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Authentication;

use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface as AuthUserInterface;

class UserAuth implements AuthUserInterface, EncoderAwareInterface
{
    /** @var UuidInterface */
    private $uuid;

    /** @var Email */
    private $email;

    /** @var Password */
    private $password;


    public static function create(UuidInterface $uuid, Credentials $credentials): self
    {
        return new self($uuid, $credentials);
    }


    private function __construct(UuidInterface $uuid, Credentials $credentials)
    {
        $this->uuid     = $uuid;
        $this->email    = $credentials->email;
        $this->password = $credentials->password;
    }


    public function __toString(): string
    {
        return $this->email->toStr();
    }


    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }


    public function getUsername(): string
    {
        return $this->email->toStr();
    }


    public function getPassword(): string
    {
        return $this->password->toStr();
    }


    public function getRoles(): array
    {
        return [
            'ROLE_USER',
        ];
    }


    public function getSalt(): ?string
    {
        return null;
    }


    public function getEncoderName(): string
    {
        return 'bcrypt';
    }


    public function eraseCredentials(): void
    {
    }
}
