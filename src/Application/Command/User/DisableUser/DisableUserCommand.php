<?php

declare(strict_types=1);

namespace App\Application\Command\User\DisableUser;

use App\Domain\User\ValueObj\Email;

class DisableUserCommand
{
    /** @var Email */
    public $username;


    /**
     * DisableUserCommand constructor.
     * @param string $username
     */
    public function __construct(string $username)
    {
        $this->username = Email::fromStr($username);
    }
}
