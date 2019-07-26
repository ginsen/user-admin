<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Domain\User\ValueObj\Credentials;
use App\Domain\User\ValueObj\Email;
use App\Domain\User\ValueObj\Password;

class SignUpCommand
{
    /** @var Credentials */
    public $credentials;


    public function __construct(string $email, string $plainPassword)
    {
        $this->credentials = new Credentials(
            Email::fromStr($email),
            Password::encode($plainPassword)
        );
    }
}
