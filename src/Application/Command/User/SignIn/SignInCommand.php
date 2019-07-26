<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Domain\User\ValueObj\Email;

class SignInCommand
{
    /** @var Email */
    public $email;

    /** @var string */
    public $plainPassword;


    public function __construct(string $email, string $plainPassword)
    {
        $this->email         = Email::fromStr($email);
        $this->plainPassword = $plainPassword;
    }
}
