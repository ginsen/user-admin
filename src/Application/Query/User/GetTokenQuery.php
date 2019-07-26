<?php

declare(strict_types=1);

namespace App\Application\Query\User;

use App\Domain\User\ValueObj\Email;

class GetTokenQuery
{
    /** @var Email */
    public $email;


    public function __construct(string $email)
    {
        $this->email = Email::fromStr($email);
    }
}
