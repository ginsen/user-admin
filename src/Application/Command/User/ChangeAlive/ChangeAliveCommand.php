<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeAlive;

use App\Domain\User\ValueObj\Email;

class ChangeAliveCommand
{
    /** @var Email */
    public $username;

    /** @var bool */
    public $active;


    /**
     * ChangeAliveCommand constructor.
     * @param string $username
     * @param bool   $active
     */
    public function __construct(string $username, bool $active)
    {
        $this->username = Email::fromStr($username);
        $this->active   = $active;
    }
}
