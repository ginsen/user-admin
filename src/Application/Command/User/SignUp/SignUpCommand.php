<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class SignUpCommand
{
    /** @var UuidInterface */
    public $uuid;

    /** @var Credentials */
    public $credentials;


    public function __construct(string $uuid, string $email, string $plainPassword)
    {
        $this->uuid        = Uuid::fromString($uuid);
        $this->credentials = new Credentials(
            Email::fromStr($email),
            Password::encode($plainPassword)
        );
    }
}
