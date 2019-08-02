<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeEmail;

use App\Domain\User\ValueObj\Email;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ChangeEmailCommand
{
    /** @var UuidInterface */
    public $uuid;

    /** @var Email */
    public $email;


    /**
     * ChangeEmailCommand constructor.
     * @param string $uuid
     * @param string $email
     */
    public function __construct(string $uuid, string $email)
    {
        $this->uuid  = Uuid::fromString($uuid);
        $this->email = Email::fromStr($email);
    }
}
