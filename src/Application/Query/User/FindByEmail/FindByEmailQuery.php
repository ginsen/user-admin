<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByEmail;

use App\Domain\User\ValueObj\Email;

class FindByEmailQuery
{
    /** @var Email */
    public $email;


    /**
     * FindByEmailQuery constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = Email::fromStr($email);
    }
}
